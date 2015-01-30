<?php
include "transformers/transformers.php";
include "RestException.php";

class Index {
  const GET = "GET";
  const POST = "POST";
  const PUT = "PUT";
  const DELETE = "DELETE";

  /**
   * @var Transformer[]
   */
  private $transformers;
  private $ifAny;

  function __construct($transformers, $ifAny) {
    $this->transformers = $transformers;
    $this->ifAny = $ifAny;
  }

  public function parseRequest() {
    $path = $_GET['request'];
    $pathExploded = explode("/", $path);

    $endpoint = $pathExploded[0];
    $verb = $pathExploded[1];
    $args = $this->clearInput(array_slice($pathExploded, 2));

    $version = $_GET['version'];

    // TODO rewrite -- deve controllare se il framework può convertire la risposta
    $accept = NULL;

    if (empty($_SERVER['HTTP_ACCEPT'])) {
      $accept = $this->ifAny;
    } else {
      $accepts = explode(",", $_SERVER['HTTP_ACCEPT']);

      foreach ($accepts as $attempt) {
        $possibleQuoteSeparator = strpos($attempt, ";");
        if ($possibleQuoteSeparator !== false) {
          $attempt = substr($attempt, 0, $possibleQuoteSeparator);
        }

        if ($this->supports($attempt) || $this->isAny($attempt)) {
          $accept = $attempt;
          break;
        }
      }

      if ($accept === NULL) {
        throw new RestException("The format(s) you asked " . $_SERVER['HTTP_ACCEPT'] . " are not allowed!", 406);
      }
    }

    $this->parseDirectly($endpoint, $verb, $args, $version, $accept);
  }

  private function supports($type) {
    foreach($this->transformers as $transformer) {
      if (in_array($type, $transformer->supports())) {
        return true;
      }
    }

    return false;
  }

  /**
   * @param $endpoint string L'endpoint richiesto
   * @param $verb string Il verbo
   * @param $args string[] Gli argomenti
   * @param $version int La versione dell'API da utilizzare
   * @param $accept string Il tipo che si aspetta come risposta
   * @param bool $directAccess bool Indica se la chiamata attuale può accedere
   * o meno agli endpoint privati. directAccess sta ad indicare "accesso diretto"
   * se true significa che la richiesta viene eseguita dall'utente (quindi tramite l'url)
   * e quindi l'accesso agli endpoint deve essere disattivato mentre se impostato su
   * false l'accesso viene eseguito tramite il codice (chiamata del metodo) e quindi
   * l'accesso agli endpoint privati è necessario.
   * Di default è impostato su true.
   *
   * @throws Exception
   * @throws RestException
   */
  public function parseDirectly($endpoint, $verb, $args, $version, $accept, $directAccess = true) {
    if (file_exists("routes_v" . $version . ".php")) {
      /** @noinspection PhpIncludeInspection */
      include "routes_v" . $version . ".php";
    } else if (file_exists("routes.php")) {
      /** @noinspection PhpIncludeInspection */
      include "routes.php";
    } else {
      throw new RestException("Version $version not supported!", 500);
    }

    if (!isset($routes)) {
      throw new RestException("Unable to load the endpoint (routes array not exists)", 500);
    }

    header("Access-Control-Allow-Orgin: *");
    header("Access-Control-Allow-Methods: *");

//    var_dump($this->isAny($accept) ? $this->ifAny : $accept);
//    var_dump(("Content-Type: application/json") == ("Content-Type: " . ($this->isAny($accept) ? $this->ifAny : $accept)));
    // without the () it doesn't work, I have no idea why
    header("Content-Type: " . ($this->isAny($accept) ? $this->ifAny : $accept));

    if (!isset($routes[$endpoint])) {
      throw new RestException("Endpoint $endpoint doesn't exists!", 404);
    }

    $currentMethod = $this->parseMethod();

    $map = $routes[$endpoint];
    $methods = $map["methods"];

    if (isset($map["private"]) && $map["private"] && $directAccess) {
      // comunico che l'endpoint non esiste anche se non è vero per evitare di diffondere
      // l'esistenza di questo endpoint privato.

      throw new RestException("Endpoint $endpoint doesn't exists!", 404);
    }

    if (!in_array($currentMethod, $methods)) {
      throw new RestException("The method $currentMethod isn't supported for $endpoint!", 405);
    }

    $this->execute($map, $currentMethod, $verb, $args, $accept);
  }

  private function execute($map, $method, $verb, $args, $accept) {
    $controllerInfo = $map["controllerFile"];

    $file = $controllerInfo["file"];
    $controller = $controllerInfo["controller"];

    if (!file_exists("controllers/" . $file)) {
      throw new RestException("The file to include doesn't exists", 500);
    }

    /** @noinspection PhpIncludeInspection */
    include "controllers/" . $file;

    $clazz = new ReflectionClass($controller);

    if (!$clazz->implementsInterface("RestController")) {
      throw new RestException("Unable to complete your request (controller)", 500);
    }

    $methodName = $this->methodTypeToMethodName($method);
    $methodReference = $clazz->getMethod($methodName);

//    $requestBody = http_get_request_body();
    $requestBody = $this->readInput();

    // per chiamare i metodi devo avere un instanza della classe
    $clazzInstance = $clazz->newInstance();
    $result = $methodReference->invoke($clazzInstance, $verb, $args, $requestBody);

    // result è una semplice array devo convertirlo nel tipo richiesto
    // in $accept

    $transformer = $this->searchTransformer($accept);

    if ($transformer === NULL) {
      throw new RestException("Your accept $accept is not supported by this API!", 500);
    }

    $output = $transformer->transform($result["response"]);

    $responseCode = $result["internal"]["responseCode"];
    header('X-PHP-Response-Code: ' . $responseCode, true, $responseCode);

    echo $output;
  }

  private function parseMethod() {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === "POST" && isset($_SERVER['HTTP_X_HTTP_METHOD'])) {
      $httpXHttpMethod = $_SERVER['HTTP_X_HTTP_METHOD'];
      switch ($httpXHttpMethod) {
        case Index::DELETE: {
          $method = Index::DELETE;
          break;
        }
        case Index::PUT: {
          $method = Index::PUT;
          break;
        }
      }
    }

    return $method;
  }

  /**
   * @param $method string
   * @return string il nome del metodo da richiamare
   * @throws Exception
   *
   * @see RestController
   */
  // TODO Maybe it should be replaced with something better?
  private function methodTypeToMethodName($method) {
    switch ($method) {
      case Index::POST: {
        return "doPost";
      }
      case Index::GET: {
        return "doGet";
      }
      case Index::PUT: {
        return "doPut";
      }
      case Index::DELETE: {
        return "doDelete";
      }
      default: {
        throw new RestException("Method not supported in the interface", 500);
      }
    }
  }

  /**
   * @param $type string
   * @return Transformer|null
   */
  private function searchTransformer($type) {
    if ($this->isAny($type)) {
      $type = $this->ifAny;
    }

    $supportedTransformers = array_filter($this->transformers, array(new TransformerForType($type), "filterTransformers"));
    // how to handle the case where multiple transformers registed for the same type?

    // per ora supporta soltanto il primo che trova
    return $supportedTransformers[0];
  }

  private function readInput() {
    // does stream_get_contents close the stream?
    return stream_get_contents(fopen("php://input", "r"));
  }

  private function isAny($accept) {
    return $accept === "*/*";
  }

  private function clearInput($inputs) {
    $output = array ();

    if (is_array($inputs)) {
      foreach($inputs as $input) {
        $output[] = trim($input);
      }
    } else {
      $output[] = trim($inputs);
    }

    return $output;
  }
}

$index = new Index($transformers, $ifAny);

try {
  $index->parseRequest();
} catch (RestException $ex) {
  $index->parseDirectly("error", NULL, array($ex->getHttpResponseCode(), $ex->getMessage()), 1, "*/*", false);
} catch (Exception $ex) {
  $index->parseDirectly("error", NULL, array(500, "Internal error"), 1, "*/*", false);
}

class TransformerForType {
  private $type;

  function __construct($type) {
    $this->type = $type;
  }

  /**
   * @param $transformer Transformer
   * @return bool
   */
  function filterTransformers($transformer) {
    return in_array($this->type, $transformer->supports());
  }
}
