<?php

/**
 * Clase para enviar al Front la pagina diseñada desde html y php. v 2.0
 * Ejemplo: $pageView = MODULES . $module . DS . $controlador . DS . $view . 'View.php'
 */
class View2
{
    public static function render($module, $view, $data = [], $folder = CONTROLLER)
    {
        # Convertir el array asociativo en objeto
        $site       = to_object($data);
        $page_view  = MODULES_PHP . $module . DS . 'views' . DS . $folder . DS . $view . 'View.php';

        if (!is_file($page_view)) {
            die(sprintf('No existe la vista %sView en la carpeta %s <br />' . $page_view, $view, $folder));
        }
        require_once GLOBAL_INCLUDES . 'head.php';
        require_once $page_view;
        require_once GLOBAL_INCLUDES . 'footer.php';

        exit();
    }
}
