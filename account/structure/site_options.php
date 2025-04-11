<?php
# El controlador por defecto, el metodo por defecto y el controlador de errores por defecto
define('DEFAULT_CONTROLLER',        'landing');
define('DEFAULT_ERROR_CONTROLLER',  'pages');
define('DEFAULT_ERROR_METHOD',      'e404');
define('DEFAULT_METHOD',            'index');
define('DEFAULT_HOME',              URL . 'landing');

# Salt del sistema
define('HOOK_TOKEN',                'iD_hook');
define('AUTH_SALT',                 'm0L1n0Sd3lC4rm3n!');
define('KEY',                       AUTH_SALT);
define('AES',                       'AES-128-ECB');

# Cookies Gestion
define('cookie_iD',                 'cookie_molinosdelcarmen');

# Sesiones Gestion
define('var_office',                'session_office');
define('var_gestion',               'session_gestion');
define('var_shop',                  'session_shop');
define('var_order',                 'session_order');
define('var_cart',                  'session_cart');
define('var_vip',                   'session_vip');
define('var_links',                 'session_links');
define('var_staff',                 'session_staff');
define('var_landing',               'session_landing');
