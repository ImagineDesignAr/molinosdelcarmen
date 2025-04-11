<?php

class Autoloader
{
    /**
     * Método encargado de ejecutar el autocargador de forma estática
     * 
     * @return void
     * */
    public static function init()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    private static function autoload($class_name)
    {
        /** 
         * Explicación: Búsqueda estática: Primero, el autoloader sigue revisando las rutas fijas (como CLASSES, controllers, models).
         * Búsqueda en módulos dinámicos: Utiliza glob() para obtener todos los directorios dentro de la carpeta account, 
         * y luego busca dentro de cada módulo si existe un archivo en las carpetas Controller o Model que coincida con el nombre de la clase.
         * ¿Cómo funciona esto?
         * Cada vez que copies un nuevo módulo dentro de la carpeta account, el autoloader automáticamente buscará los archivos de controladores 
         * y modelos dentro de las carpetas de ese módulo (Controller y Model). 
         * No tienes que modificar el autoload manualmente; simplemente asegúrate de que los controladores y modelos estén ubicados correctamente dentro del módulo. */

        # Ruta principal de las clases
        if (is_file(CLASSES . $class_name . '.php')) {
            require_once CLASSES . $class_name . '.php';
            return;
        }
        # Ruta dinámica para cargar módulos dentro de "account"
        $account_modules = glob(MODULES_PHP . '*', GLOB_ONLYDIR); # Obtener todos los directorios en 'account'
        foreach ($account_modules as $module) {
            # Buscar controlador o modelo en el módulo
            $controller_path    = $module . DS . 'controllers' . DS . $class_name . '.php';
            $model_path         = $module . DS . 'models' . DS . $class_name . '.php';

            if (is_file($controller_path)) {
                require_once $controller_path;
                return;
            } elseif (is_file($model_path)) {
                require_once $model_path;
                return;
            }
        }
    }
}
