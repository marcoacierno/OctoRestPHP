<?php
include "RestController.php";

class AbstractRestController implements RestController {
  public function doGet($verb, $args, $requestBody) {}
  public function doPost($verb, $args, $requestBody) {}
  public function doPut($verb, $args, $requestBody) {}
  public function doDelete($verb, $args, $requestBody) {}
}