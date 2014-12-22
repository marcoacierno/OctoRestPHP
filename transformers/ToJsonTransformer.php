<?php
include "Transformer.php";

class ToJsonTransformer implements Transformer {
  public function supports() {
    return array(
        "application/json",
    );
  }

  public function transform($input) {
    return json_encode($input);
  }
}