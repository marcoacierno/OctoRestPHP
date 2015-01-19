<?php

/**
 * L'exception deve essere generata ogni volta si verifica un errore all'interno
 * di un controller e si vuole notificare l'utente dell'errore. Un esempio di errore
 * è quando l'utente non passa un argomento necessario o quando viene richiesto
 * un verbo non supportato.
 *
 * Il richiamate del controller si occuperà di catturare l'eccezione e di mostrare
 * all'utente un messaggio di errore adeguato nel formato da lui richiesto.
 */
class RestException extends Exception {
  /**
   * @var int
   */
  private $httpResponseCode;

  /**
   * @param string $message
   * @param int $httpResponseCode
   */
  function __construct($message, $httpResponseCode) {
    parent::__construct($message);
    $this->httpResponseCode = $httpResponseCode;
  }

  /**
   * @return int
   */
  public function getHttpResponseCode() {
    return $this->httpResponseCode;
  }
}