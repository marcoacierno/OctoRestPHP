<?php
include "RestController.php";

class ErrorController implements RestController {
  /**
   * Richiamato quando il controller riceve una richiesta con il metodo get
   *
   * @param $verb string Il verbo
   * @param $args string[] Gli argomenti
   * @return array L'array che conterrà la risposta
   */
  public function doGet($verb, $args, $requestBody) {
    return $this->error($args);
  }

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo post
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @param $requestBody
   * @return array l'Array che conterrà la risposta
   */
  public function doPost($verb, $args, $requestBody) {
    return $this->error($args);
  }

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo put
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @param $requestBody
   * @return array l'Array che conterrà la risposta
   */
  public function doPut($verb, $args, $requestBody) {
    return $this->error($args);
  }

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo delete
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @return array l'Array che conterrà la risposta
   */
  public function doDelete($verb, $args, $requestBody) {
    return $this->error($args);
  }

  private function error($args) {
    return array(
      "internal" => array(
        "responseCode" => $args[0]
      ),
      "response" => array(
        "message" => $args[1]
      )
    );
  }
}