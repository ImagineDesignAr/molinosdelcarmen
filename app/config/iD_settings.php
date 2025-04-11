<?php
# Definir el uso horario o timezone del sistema
date_default_timezone_set('America/Argentina/Buenos_Aires');
# Lenguaje
define('LANG', 'es');

# Puerto y la URL del sitio
define('BASEPATH',      '/molinosdelcarmen/page/');
define('IS_LOCAL',      in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
define('PORT',          '80');
define('LOCALHOST_IP',  'localhost'); # IP para que funcione en la misma red
define('PROTOCOL',      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"); # Detectar si está en HTTPS o HTTP
define('HOST',          $_SERVER['HTTP_HOST'] === 'localhost' ? LOCALHOST_IP : $_SERVER['HTTP_HOST']);  # Dominio o host localhost.com tudominio.com
define('REQUEST_URI',   $_SERVER["REQUEST_URI"]); # Parametros y ruta requerida
define('URL',           PROTOCOL.'://'.HOST.BASEPATH); # URL del sitio
define('CUR_PAGE',      PROTOCOL.'://'.HOST.REQUEST_URI); # URL actual incluyendo parametros get
