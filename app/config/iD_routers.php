<?php
# Cliente
define('ACCOUNTS',          'account');

# Las rutas de directorios y archivos
define('DS',                DIRECTORY_SEPARATOR);
define('DS_URL',            '/');
define('ROOT',              getcwd() . DS);

define('APP',               ROOT . 'app' . DS);
define('CLASSES',           APP . 'classes' . DS);
define('CONFIG',            APP . 'config' . DS);
define('FUNCTIONS',         APP . 'functions' . DS);
define('PLUGINS_PHP',       APP . 'plugins' . DS);
define('LOGS',              APP . 'logs' . DS);
define('GLOBAL_INCLUDES',   APP . '_includes' . DS);

# Assets de Frameworks 
define('VENDOR',            URL . 'vendor' . DS_URL);
define('STYLES',            VENDOR . 'styles' . DS_URL);
define('LIBS',              VENDOR . 'libs' . DS_URL);
define('FR_IMAGES',         VENDOR . 'fr_images' . DS_URL);

# Carpetas del cliente
define('ROOT_CLIENT',       ROOT . ACCOUNTS . DS);
define('URL_CLIENT',        URL . ACCOUNTS . DS_URL);
define('MODULES_PHP',       ROOT_CLIENT . 'modules' . DS);


define('FOLDER_JSON',       ROOT_CLIENT . 'json' . DS);
define('FOLDER_SETS',       ROOT_CLIENT . 'sets' . DS);
define('FOLDER_STRUCTURE',  ROOT_CLIENT . 'structure' . DS);
define('FOLDER_ASSETS',     ROOT_CLIENT . 'assets' . DS);
define('DB_FOLDER',         FOLDER_ASSETS . 'db' . DS);

define('FOLDER_IMAGES',     FOLDER_ASSETS . 'images' . DS);
define('FOLDER_FILES',      FOLDER_ASSETS . 'files' . DS);
define('FOLDER_CERT',       FOLDER_ASSETS . 'certs' . DS);
define('FOLDER_QR',         FOLDER_ASSETS . 'qr' . DS);
define('FOLDER_EMAIL',      FOLDER_ASSETS . 'email_template' . DS);
define('FOLDER_FONTS',      FOLDER_ASSETS . 'fonts' . DS);
define('FOLDER_DB',         FOLDER_ASSETS . 'db' . DS);

define('MODULES',           URL_CLIENT . 'modules' . DS_URL);
define('ASSETS',            URL_CLIENT . 'assets' . DS_URL);
define('IMAGES',            ASSETS . 'images' . DS_URL);
define('FILES',             ASSETS . 'files' . DS_URL);
define('QRS',               ASSETS . 'qr' . DS_URL);

# Rutas globales e imagenes del Framework FlexiMVC
define('url_whatsapp',      'https://wa.me/');
define('url_afip',          'https://www.afip.gob.ar/fe/qr/?p=');
define('logo_fr',           FR_IMAGES . 'logo_fr.png');
define('logo_old',          FR_IMAGES . 'id.png');
define('logo_dark',         FR_IMAGES . 'id_dark.png');
define('logo_blue',         FR_IMAGES . 'id_blue.png');
define('logo_green',        FR_IMAGES . 'id_green.png');
define('logo_indigo',       FR_IMAGES . 'id_indigo.png');
define('logo_white',        FR_IMAGES . 'id_white.png');
define('logo_maintenance',  FR_IMAGES . 'mantenimiento.png');
define('logo_stop',         FR_IMAGES . 'stop.png');
define('logo_404',          FR_IMAGES . 'error.svg');
define('logo_clock',        FR_IMAGES . 'clock.svg');
define('logo_widget',       FR_IMAGES . 'widget-img.svg');
define('logo_offline',      FR_IMAGES . 'auth-offline.svg');
define('logo_afip',         FR_IMAGES . 'afip.png');
