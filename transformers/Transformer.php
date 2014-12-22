<?php

/**
 * Una classe che implementa questa interfaccia
 * dichiara di essere capace di transformare
 * un array ricevuto come input
 * in una stringa.
 *
 * Un esempio di un transformatore è {@see ToJsonTransformer}
 * che converte l'array ricevuto come input nel formato Json.
 */
interface Transformer {
  /**
   * @return String[] Un array contenente tutti i tipi che il transformatore
   * può convertire con successo.
   */
  public function supports();

  /**
   * @param $input array La risposta da convertire
   * @return string La risposta convertita a testo nel formato
   * del trasformatore
   */
  public function transform($input);
}