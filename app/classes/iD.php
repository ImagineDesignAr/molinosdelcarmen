<?php

# private 		= no puede ser utilizada en ningun otro lado mas que dentro de la clase dueña
# protected 	= puede ser utilizada por la clase dueña e hijas, pero no por fuera
# public		= puede ser utilizada por fuera de la clase, dentro de la clase dueña e hijas

# Version de los Plugins
# Vue           : 2.7.14
# Axios         : 1.5.0
# Sweetalert2   : 11.4.17
# Toastify-js   : 1.12.0
# iziToast      : 1.4.0
# Afip SDK      : 0.5.1

# Clase para inicializar toda la WEB. v 4.0.0 01/01/2025
class iD
{
    #  Propiedades del Framework
    private $framework = 'FlexiMVC Framework';
    private $version = '4.0.0 BETA';
    private $uri = [];

    #  La función principal que se ejecuta al instanciar nuestra clase
    function __construct()
    {
        $this->init();
    }

    /**
     * Método para ejecutar cada "método" de forma subsecuente
     * @return void
     */
    private function init()
    {
        #  Cargar métodos esenciales del framework y del cliente
        $this->init_load_configs();
        $this->init_load_structure(); // Carga las configuraciones específicas del cliente
        $this->init_session();
        
        $this->init_load_function();
        $this->init_load_sets();
        $this->init_load_plugins();
        $this->init_autoload();
        $this->init_helpers();
        $this->init_csrf();

        # Mostrar errores si el sistema está en producción
        if (site_production === true) {
            ini_set('display_errors', 'Off');
            error_reporting(E_ALL & ~E_WARNING);
        }

        $this->dispatch();
        return;
    }

    private function init_session()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name(cookie_iD);
            session_start();
        }
    }

    /**
     * Método para cargar las configuraciones del framework
     * @return void
     */
    private function init_load_configs()
    {
        # CONFIGURACIÓN DEL FRAMEWORK
        $file = 'app/config/iD_settings.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;

        $file = 'app/config/iD_routers.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;
    }

    /**
     * Método para cargar las configuraciones y módulos del cliente específico
     * @return void
     */
    private function init_load_structure()
    {
        $path = FOLDER_STRUCTURE;
        $dir = opendir($path);
        while ($elemento = readdir($dir)) {
            if ($elemento != "." && $elemento != "..") {
                $file = $path . $elemento;
                if (!is_file($file)) {
                    die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
                }
                require_once $file;
            }
        }
        closedir($dir);
    }

    private function init_load_function()
    {
        $file = FUNCTIONS . 'iD_core_functions.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;
        return;
    }

    private function init_load_sets()
    {
        $path = FOLDER_SETS;
        $dir = opendir($path);
        while ($elemento = readdir($dir)) {
            if ($elemento != "." && $elemento != "..") {
                $file = $path . $elemento;
                if (!is_file($file)) {
                    die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
                }
                require_once $file;
            }
        }
        closedir($dir);
    }
    private function init_helpers()
    {
        $account_modules = glob(MODULES_PHP . '*', GLOB_ONLYDIR); # Obtener todos los directorios en 'account'
        // Verificar si la carpeta 'helpers' existe

        foreach ($account_modules as $module) {
            $helpers_path = $module . DS . 'helpers' . DS;

            if (!is_dir($helpers_path)) {
                ##printf('Advertencia: El directorio %s no existe. Saltando módulo: %s<br>', $helpers_path, $module);
                continue; // Saltar al siguiente módulo si no hay carpeta helpers
            }
            $dir = opendir($helpers_path); // Intentar abrir el directorio
            if (!$dir) {
                die(sprintf('Error: No se pudo abrir el directorio %s', $helpers_path));
            }
            while ($elemento = readdir($dir)) {
                if ($elemento != "." && $elemento != "..") {
                    $file = $helpers_path . $elemento;
                    if (!is_file($file)) {
                        die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
                    }
                    require_once $file;
                }
            }
            closedir($dir); // Siempre cerrar el directorio al final
        }
    }

    private function init_load_plugins()
    {
        $plugins = [
            //PLUGINS_PHP . 'afip_sdk/Afip.php',
            PLUGINS_PHP . 'fpdf/fpdf.php',
            PLUGINS_PHP . 'qr/qrlib.php',
            PLUGINS_PHP . 'mobile_detect/Mobile_Detect.php'
        ];

        foreach ($plugins as $file) {
            if (!is_file($file)) {
                die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
            }
            require_once $file;
        }
    }

    private function init_autoload()
    {
        require_once CLASSES . 'Autoloader.php';
        Autoloader::init();
        return;
    }

    private function init_csrf()
    {
        $csrf = new Csrf();
        define('CSRF_TOKEN', $csrf->get_token());
    }

    private function filter_url()
    {
        if (isset($_GET['uri'])) {
            $uri = $_GET['uri'];
            $uri = rtrim($uri, '/');
            $uri = filter_var($uri, FILTER_SANITIZE_SPECIAL_CHARS);
            $this->uri = explode('/', $uri);
        }
    }

    private function dispatch()
    {
        $this->filter_url();
        $current_controller = isset($this->uri[0]) ? strtolower($this->uri[0]) : DEFAULT_CONTROLLER;
        unset($this->uri[0]);
        # Comprobar primero si es una urls personalizada
        Urls::get($current_controller);
        $controller = $current_controller . 'Controller';
        if (!class_exists($controller)) {
            check_url($current_controller);
        }

        $method = isset($this->uri[1]) ? str_replace('-', '_', strtolower($this->uri[1])) : DEFAULT_METHOD;
        unset($this->uri[1]);
        define('CONTROLLER', $current_controller);
        define('METHOD', $method);
        $controller = new $controller;
        $params     = array_values(empty($this->uri) ? [] : $this->uri);

        if (method_exists($controller, $method)) {
            (empty($params)) ? call_user_func([$controller, $method]) : call_user_func_array([$controller, $method], $params);
        } else {
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                json_response(404, null, 'No posee acceso al metodo');
            } else {
                Redirect::to(DEFAULT_ERROR_CONTROLLER);
            }
        }
    }

    public static function imagine()
    {
        $iD = new self();
        return;
    }
}
