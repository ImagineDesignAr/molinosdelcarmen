<?php

/**
 * Renderiza una vista de un módulo, con la opción de incluir un layout de administrador.
 *
 * @param string $module El nombre del módulo al que pertenece la vista.
 * @param string $view El nombre de la vista a cargar (sin extensión).
 * @param array $data [Opcional] Datos que serán convertidos en un objeto accesible desde la vista.
 * @param bool|null $mode [Opcional] Si es true, incluye el layout de administrador (header, topbar, etc.).
 *
 * Propósito:
 * - Cargar y mostrar una vista específica dentro de un módulo.
 * - Convertir los datos dinámicos en un objeto para facilitar su acceso en la vista.
 * - Condicionalmente estructurar la vista dentro de un layout con componentes globales.
 *
 * Ejemplo de Uso:
 * render('dashboard', 'index', ['title' => 'Dashboard'], true);
 * $pageView = MODULES . $module . DS . $controlador . DS . $view . 'View.php'
 */
class View3
{
    public static function render($module, $view, $data = [], $admin = null)
    {
        # Convertir el array asociativo en objeto
        $site       = to_object($data);
        $page_view  = MODULES_PHP . $module .  DS . 'views' . DS . $view . 'View.php';

        if (!is_file($page_view)) {
            die(sprintf('No existe la vista'));
        }

        require_once GLOBAL_INCLUDES . 'head.php';

        $admin && print '<div id="layout-wrapper">';
        $admin && require_once GLOBAL_INCLUDES . 'header.php';
        $admin && require_once GLOBAL_INCLUDES . 'topbar.php';
        $admin && print '<div class="vertical-overlay"></div>';
        require_once $page_view;
        $admin && print '</div>';

        require_once GLOBAL_INCLUDES . 'footer.php';

        exit();
    }
}
