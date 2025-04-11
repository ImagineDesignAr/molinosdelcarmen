<?php

/**
 * Clase para redireccionar a otras webs. v 1.0
 */ 
class Redirect
{

    private $location;

    /**
     * Metodo para redirigir al usuario a una seccion determinada
     * 
     * @param string $location
     * @return void
     */

    public static function to($location)
    {
        $self = new self();
        $self->location = $location;
             
        # Si las cabeceras ya fueron envíadas
        if (headers_sent()) {
            $tempScript = 
            '<script type="text/javascript">
                window.location.href="%s"
            </script>
            <noscript>
                <meta http-equiv="refresh" content="0;url=%s" />
            </noscript>';
            
            echo sprintf($tempScript, URL . $self->location, URL . $self->location ); 
            die();
        }

        # Cuando pasamos una URL externa a nuestro sitio

        if (strpos($self->location, 'http') !== false){
            header('Location: '.$self->location);
            die();
        }

        # Redirigir usuario a otra seccion
        header('Location: '.URL.$self->location);
        die();
    }
}
