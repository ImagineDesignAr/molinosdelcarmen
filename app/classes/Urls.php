<?php

/**
 * Metodo para ejecutar links o comandos directos de raiz. La consulta se origina en el archivo urls.
 * 
 * @return void
 */

class Urls

{
    public static function get($url)
    {
        try {
            (array_key_exists($url, urls)) ? Redirect::to(urls[$url]) : null;
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
