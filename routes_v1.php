<?php
/**
 * It's important to have keep the name "routes"
 */
$routes = array(
    /**
     * L'endpoint è la parte che segue la versione nell'url.
     * Esempio url:
     *
     * api/v1/endpoint -> api/v1/users
     */
    //"users" => array(
        /**
         * I metodi supportati da questo endpoint.
         *
         * In base ad ogni metodo vengono richiati metodi diversi nel controller che sarà specificato
         * più avanti.
         *
         * In questo esempio l'endpoint supporta i metodi GET e PUT
         */
    //    "methods" => array(
    //        "GET",
    //        "POST"
    //    ),
        /**
         * Il controller che dovrà rispondere alla richiesta
         */
    //    "controllerFile" => array(
          /**
           * Il file da includere (contiene la classe da implementare)
           */
    //      "file" => "UsersController.php",
          /**
           * Il nome della classe
           */
    //      "controller" => "UsersController",
    //    ),
    //),
    
    /**
     * L'endpoint error è un endpoint interno
     * utilizzato quando qualcosa va storto
     * con gli altri endpoint.
     *
     * Non può essere richiesto direttamente
     */
    "error" => array(
        "methods" => array("GET", "POST", "PUT", "DELETE"),
        "controllerFile" => array(
            "file" => "ErrorController.php",
            "controller" => "ErrorController"
        ),
        /**
         * Impostando l'endpoint su private => true
         * utilizzare questo endpoint direttamente diventa
         * impossibile.
         */
        "private" => true
    )
);
