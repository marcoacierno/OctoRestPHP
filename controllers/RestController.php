<?php

/**
 * Tutte le classi che fungono da rest controller
 * devono implementare questa interfaccia
 * per poter ricevere le chiamate a cui sono state
 * registrate nel file routes.
 *
 * Tutti i metodi in questa interfaccia DEVONO ritornare
 * un array che contiene la risposta da ritornare nell'indice response,
 * mentre in 'internal' può essere inserito il responseCode.
 */
interface RestController {
  /**
   * Richiamato quando il controller riceve una richiesta con il metodo get
   *
   * @param $verb string Il verbo
   * @param $args string[] Gli argomenti
   * @return array L'array che conterrà la risposta
   */
  public function doGet($verb, $args, $requestBody);

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo post
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @param $requestBody
   * @return array l'Array che conterrà la risposta
   */
  public function doPost($verb, $args, $requestBody);

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo put
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @param $requestBody
   * @return array l'Array che conterrà la risposta
   */
  public function doPut($verb, $args, $requestBody);

  /**
   * Richiamato quando il controller riceve una richiesta con il metodo delete
   *
   * @param $verb string il verbo
   * @param $args string[] Gli argomenti
   * @return array l'Array che conterrà la risposta
   */
  public function doDelete($verb, $args, $requestBody);
}