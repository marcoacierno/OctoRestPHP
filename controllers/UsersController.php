<?php
include "RestController.php";

class UsersController implements RestController {
  public function doGet($verb, $args, $requestBody) {
    switch ($verb) {
      case "user": {
        return array(
            "internal" => array(
              "responseCode" => 200
            ),
            "response" => array(
              "user" => $args[0],
              "hello" => $requestBody
            ),
        );
      }
      default: return array("internal" => array("responseCode" => "200"), "response" => array("a" => 1, "b" => 2));
    }
  }

  public function doPost($verb, $args, $requestBody) {
    return array(
        "internal" => array(
          "responseCode" => 200,
        ),
        "response" => array(
          "hello" => "lol",
          "body" => $requestBody
        ),
    );
  }

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo put
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @return array l'Array che conterrà la risposta
   */
  public function doPut($verb, $args, $requestBody) {
    // TODO: Implement doPut() method.
  }

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo delete
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @return array l'Array che conterrà la risposta
   */
  public function doDelete($verb, $args, $requestBody) {
    // TODO: Implement doDelete() method.
  }
}