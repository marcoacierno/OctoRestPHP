<?php
class RestException extends Exception {
  /**
   * @var int
   */
  private $errorCode;
  /**
   * @var int
   */
  private $httpResponseCode;

  /**
   * @param string $message
   * @param int $errorCode
   * @param int $httpResponseCode
   */
  function __construct($message, $errorCode, $httpResponseCode) {
    parent::__construct($message);

    $this->errorCode = $errorCode;
    $this->httpResponseCode = $httpResponseCode;
  }

  /**
   * @return int
   */
  public function getErrorCode() {
    return $this->errorCode;
  }

  /**
   * @return int
   */
  public function getHttpResponseCode() {
    return $this->httpResponseCode;
  }
}