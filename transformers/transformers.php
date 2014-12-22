<?php
// Includere tutti i transformers supportati nell'array

include "ToJsonTransformer.php";

/**
 * Transformatori supportati
 */
$transformers = array(
  new ToJsonTransformer()
);

/**
 * Se la richiesta contiene *\/*
 * sarà richiamato il transformatore
 * che supporta il tipo specificato
 * in questa stringa
 */
$ifAny = "application/json";