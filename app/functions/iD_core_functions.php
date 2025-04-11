<?php
// Funcion para obtener todo el contenido de los .json en un array (GUARDADOS EN account/json).
function json_get($file, $array = true)
{
  $_path = FOLDER_JSON . $file . '.json';
  if (file_exists($_path)) {
    $json_content = file_get_contents($_path);
    if ($array) {
      $contents = json_decode($json_content, true);
      if (json_last_error() != JSON_ERROR_NONE) {
        die("Error al decodificar el JSON: " . json_last_error_msg());
      }
    }
  } else {
    die("El archivo no existe: $file");
  }
  return $contents;
}
function send_email($to, $subject, $template, $data = [])
{
  // send_email("usuario@example.com", "Código de validación", "send_code", [$site_title, $site_logo, $validate_code]);
  // send_email("usuario@example.com", "Recuperación de contraseña", "send_pass", [$site_title, $site_logo, $password]);

  // Definir los datos base
  $email_soporte = email_soporte;
  $headers = sprintf("From:%s\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8\r\n", $email_soporte);

  // Cargar el contenido de la plantilla
  $template_path = FOLDER_EMAIL . $template . '.php';

  if (!file_exists($template_path)) {
    return 'Error: Plantilla no encontrada.';
  }

  $content = file_get_contents($template_path);

  // Reemplazar placeholders con datos reales
  $content = vsprintf($content, $data);

  // Enviar el correo
  return mail($to, $subject, $content, $headers);
}

function export_to_csv($array_header, $array_data, $name_file = null)
{
  // Validar que haya datos para exportar
  if (empty($array_header) || empty($array_data)) {
    return 'Error: No hay datos para exportar.';
  }

  // Definir el nombre del archivo
  $name_file = $name_file ?: uniqid('csv_', true);
  $filename = $name_file . '.csv';
  $file_path = FOLDER_FILES . $filename;

  try {
    // Abrir el archivo en modo escritura
    $file = fopen($file_path, 'w');

    if (!$file) {
      throw new Exception("No se pudo crear el archivo: $file_path");
    }

    // Escribir la cabecera en el CSV
    fputcsv($file, $array_header, ';');

    // Escribir los datos en el CSV
    foreach ($array_data as $row_data) {
      fputcsv($file, $row_data, ';');
    }

    // Cerrar el archivo
    fclose($file);

    return FILES . $filename;
  } catch (Exception $e) {
    return 'Error al generar archivo. Msg: ' . $e->getMessage();
  }
}

/** Funcion para subir archivos al servidor
 *
 * @return string
 */
function upload_files($file)
{
  $temporary_destination = $file['tmp_name'];
  $filename = $file['name'];
  $fileSize = $file['size'];
  $fileType = $file['type'];

  $file_extension = explode('.', $filename);
  $file_extension = strtolower(end($file_extension));
  $new_filename = generate_ref() . '.' . $file_extension;
  $allowedExtn = ["jpg", "jpeg", "png", "pdf", "docx", "doc"];

  if (in_array($file_extension, $allowedExtn)) {
    switch ($file_extension) {
      case 'jpg':
      case 'jpeg':
      case 'png':
        $destination_route  = FOLDER_IMAGES;
        $destination_folder = IMAGES;
        break;
      case 'pdf':
      case 'docx':
      case 'doc':
        $destination_route  = FOLDER_FILES;
        $destination_folder = FILES;
        break;
    }
    $end_file = $destination_route . $new_filename;
    if (move_uploaded_file($temporary_destination, $end_file)) {
      return $destination_folder . $new_filename;
    }
  }
  return false;
}
/** Funcion para subir archivos al servidor
 *
 * @return string
 */
/* function upload_files($file)
{
  $temporary_destination = $file['tmp_name'];
  $filename = $file['name'];
  $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  $new_filename = generate_ref() . '.' . $file_extension;
  $allowedExtn = ["jpg", "jpeg", "png", "pdf", "docx", "doc"];

  if (in_array($file_extension, $allowedExtn)) {
    switch ($file_extension) {
      case 'jpg':
      case 'jpeg':
      case 'png':
        $destination_route  = FOLDER_IMAGES;
        $destination_folder = IMAGES;
        $end_file = $destination_route . $new_filename;

        // Redimensionar la imagen y guardarla en la ruta de destino
        if (resize_image($temporary_destination, 1000, 1000, $end_file)) {
          return $destination_folder . $new_filename;
        }
        break;

      case 'pdf':
      case 'docx':
      case 'doc':
      case 'csv':
        $destination_route  = FOLDER_FILES;
        $destination_folder = FILES;
        $end_file = $destination_route . $new_filename;

        if (move_uploaded_file($temporary_destination, $end_file)) {
          return $destination_folder . $new_filename;
        }
        break;
    }
  }
  return false;
} */
/* function resize_image($file, $max_width, $max_height, $destination)
{
  list($original_width, $original_height, $image_type) = getimagesize($file);
  $ratio = $original_width / $original_height;
  ($max_width / $max_height > $ratio) ? $max_width = $max_height * $ratio : $max_height = $max_width / $ratio;
  $new_image = imagecreatetruecolor(round($max_width), round($max_height));
  switch ($image_type) {
    case IMAGETYPE_JPEG:
      $source_image = imagecreatefromjpeg($file);
      break;
    case IMAGETYPE_PNG:
      $source_image = imagecreatefrompng($file);
      break;
    default:
      return false;
  }
  imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, round($max_width), round($max_height), round($original_width), round($original_height));
  switch ($image_type) {
    case IMAGETYPE_JPEG:
      imagejpeg($new_image, $destination, 90); // Guardar imagen JPEG con calidad 90
      break;
    case IMAGETYPE_PNG:
      imagepng($new_image, $destination);
      break;
  }

  imagedestroy($new_image);
  imagedestroy($source_image);

  return true;
} */
/** Comprobar un url */
function cvs_to($filename)
{
  try {
    $filename = FILES . 'orderDetails.csv';

    $file = fopen($filename, 'r');

    $headers = fgetcsv($file);
    $headers = array_map(function ($header) {
      return  replace_chars($header, true);
    }, $headers);

    $data = [];
    while ($row = fgetcsv($file)) {
      $data[] = array_combine($headers, $row);
    }
    fclose($file);

    return $data;
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
/** Comprobar un url */
function destroy($cookie, $time = 3600)
{
  try {
    setcookie($cookie, '', time() - $time, '/');
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
/** Comprobar un url */
function check_url($uri)
{
  try {
    if (array_key_exists($uri, urls)) {
      Redirect::to(urls[$uri]);
    } else {
      Redirect::to(DEFAULT_ERROR_CONTROLLER);
    }
  } catch (Exception $e) {
    return $e->getMessage();
  }
}

/** Calcular margen de rentabilidad */
function profitability($a = 0, $p = 15)
{
  try {
    $x100 = $p / 100;
    return $a / (1 - $x100);
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
/** Calcula porcentaje */
function x100($a = 0, $p = 21)
{
  try {
    $x100 = $p / 100;
    return $a * $x100;
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
/** Comprueba las paginas disponibles para el perfil */
function get_pages($profile)
{
  $profiles = profiles[$profile];
  return $profiles;
}
/** Comprueba el formulario enviado por el cliente (FrontEnd) */
function check_form($form_name = 'form')
{
  try {
    (!isset($_POST[$form_name])) ? json_response(400, null, 'Formulario incorrecto') : $forms =  json_decode($_POST[$form_name], true);
    return $forms;
  } catch (Exception $e) {
    json_response(400, null, 'Formulario incorrecto - ' . $e);
  }
}
/** Obtener años de una fecha hasta el dia de hoy
 *
 * @param string $date
 * @return string
 */
function date_age($date_query = null)
{
  try {
    if ($date_query == null) {
      return 0;
    }
    $date_query = new DateTime($date_query);
    $date_now   = new DateTime(date_js());
    $return     = $date_query->diff($date_now);

    return $return->y;
  } catch (Exception $e) {
    return 0;
  }
}
/** Obtener año
 *
 * @param string $date
 * @return string
 */
function date_year($date = null)
{
  try {
    if ($date == null) {
      $date = date_js();
    }
    return date("Y", strtotime($date));
  } catch (Exception $e) {
    return 2024;
  }
}
/** Obtener mes
 *
 * @param string $date
 * @return string
 */
function date_month($date = null)
{
  try {
    if ($date == null) {
      $date = date_js();
    }
    return date("m", strtotime($date));
  } catch (Exception $e) {
    return 1;
  }
}
/** Obtener ultimo dia del mes
 *
 * @param string $date
 * @return string
 */
function date_last($date = null)
{
  try {
    if ($date == null) {
      $date = date_js();
    }
    $datex    = explode("-", $date);
    $year_now = date("Y");
    $year     = $datex[0];
    $month    = $datex[1];
    $day      = $datex[2];

    if ($year < 1900 || $year > $year_now) {
      return date_js();
    }
    if (!checkdate($month, $day, $year)) {
      return date_js();
    }

    return date("Y-m-t", strtotime($date));
  } catch (Exception $e) {
    return date_js();
  }
}
/** Obtener primer dia del mes
 *
 * @param string $date
 * @return string
 */
function date_first($date = null)
{
  try {
    if ($date == null) {
      $date = date_js();
    }

    $datex    = explode("-", $date);
    $year_now = date("Y");
    $year     = $datex[0];
    $month    = $datex[1];
    $day      = $datex[2];

    if ($year < 1900 || $year > $year_now) {
      return date_js();
    }
    if (!checkdate($month, $day, $year)) {
      return date_js();
    }

    return sprintf('%s-%s-%s', $year, $month, '01');
  } catch (Exception $e) {
    return date_js();
  }
}
/** Comprueba una fecha si es valida
 *
 * @param string $date
 * @return boolean
 */
function check_date($date = null)
{
  try {
    if ($date === null) {
      return false;
    }

    // Intentar convertir la fecha usando DateTime
    $date_time = DateTime::createFromFormat("d/m/Y H:i", $date);

    // Si la conversión falla, intentamos con otro formato (por si viene solo con fecha)
    if (!$date_time) {
      $date_time = DateTime::createFromFormat("Y-m-d", $date);
    }

    if (!$date_time) {
      return false;
    }

    // Extraer año, mes y día
    $year     = (int) $date_time->format("Y");
    $month    = (int) $date_time->format("m");
    $day      = (int) $date_time->format("d");
    $year_now = (int) date("Y");

    // Validar rango de años
    if ($year < 1900 || $year > $year_now) {
      return false;
    }

    // Validar que la fecha sea válida
    return checkdate($month, $day, $year);
  } catch (Exception $e) {
    return false;
  }
}
/** Genera un string de palabras claves
 *
 * @param array $array
 * @return string
 */
function generate_keywords($array)
{
  $keywords = '';
  foreach ($array as $value) {
    if ($value != '') {
      $tmp = (string) str_replace(" ", ",", $value);
      $keywords .= $tmp . ',';
    }
  }
  $keywords = substr($keywords, 0, -1);
  $keywords = mb_strtolower($keywords);
  return $keywords;
}
/** Comprueba el valor y devuelve un boolean (true or false)
 *
 * @param array $array
 * @return string
 */
function boolean_return($status)
{
  if ($status != 0) {
    return true;
  } else {
    return false;
  }
}
/** Guarda dentro del log un error y envia una notificacion al usuario
 *
 * @param string $msg
 * @param string $controller
 * @return string
 */
function log_error($msg, $controller)
{
  logger($msg . '. IP: ' . get_user_ip(), 'error');
  Flasher::new($msg, 'danger');
  Redirect::to(URL . $controller . '/login');
}
/** Carga los estilos enviados desde el controlador en formato de array
 *
 * @param array $array
 * @return string
 */
function load_styles($style_array)
{
  $version  = '1.0.0'; // si querés manejarlo dinamico time()
  $css      = '<link href="%s?v=' . $version . '" rel="stylesheet" type="text/css" />'  . "\n";
  $js       = '<script src="%s?v=' . $version . '"></script>' . "\n";
  $output   = '';

  if (is_array($style_array)) {
    $style_array = to_object($style_array);
  }
  foreach ($style_array as $item) {
    $extension = pathinfo($item, PATHINFO_EXTENSION);

    if ($extension == 'css') {
      $output .= sprintf($css, $item);
    } else {
      $output .= sprintf($js, $item);
    }
  }

  return $output;
}
/** Convierte un array en objeto
 *
 * @param array $array
 * @return object
 */
function to_object($array)
{
  return json_decode(json_encode($array));
}
/** Convierte un array en json
 *
 * @param array $array
 * @return string
 */
function to_json($array)
{
  $array = json_encode($array);
  return $array;
}
/** Convierte json a array
 *
 * @return array
 */
function to_array($json, $brackets = true)
{
  # Obtiene primer caracter. Si es brackets ( [ ) no se los agrega
  if ($json != '') {
    $c = substr($json, 0, 1);
    if ($brackets) {
      if ($c != '[') {
        $json = '[' . $json . ']';
      }
    }
    return json_decode($json, true);
  } else {
    return [];
  }
}
/** Busca detro del perfil solicitado si tiene acceso al metodo(,)
 *
 * @param string $controller
 * @param array $profile
 * @return void
 */
function check_methods($controller, $method, $profile)
{
  try {
    if (isset(profiles[$profile]['methods'][$controller])) {
      $methods = profiles[$profile]['methods'][$controller];
      if (!in_array($method, $methods)) {
        json_response(401, null, 'Acceso denegado al metodo solicitado');
      }
    } else {
      json_response(401, null, 'Acceso denegado al metodo solicitado');
    }
  } catch (Exception $e) {
    json_response(404, null, $e->getMessage());
  }
}
/** Trae valor desde el POST y luego comprueba criterios
 * [trim]       - Quita los espacios en el string
 * [clean]      - Convierte caracteres especiales en entidades HTML
 * [strtolower] - Convierte una cadena de caracteres a minúsculas
 * [ucwords]    - Convierte a mayúsculas el primer caracter de cada palabra de una cadena
 * [strtoupper] - Convierte un string a mayúsculas
 * [hash_pass]  - Hashea el string enviado
 * [positive]   - Convierte el string a numero y fuerza el positivo
 * [encrypt]    - Encripta la cadena de texto 
 * [notnull]    - Si la cadena viene nula envio error
 * [boolean]    - Convierte un valor booleano a numerico para almacenar en mysql
 * [image]      - Quita la ruta predeterminada antes de enviar a la base de datos
 * [file]       - Quita la ruta predeterminada antes de enviar a la base de datos
 * @param array $dataForm
 * @param string $field
 * @param array $params
 * @param string $text_return
 * @return
 */
function get_form($data_form, $field, $params = [], $text_default = '')
{
  $text_return = '';
  if ($data_form !== null) {
    if (!is_array($data_form)) {
      $data_form = json_decode($data_form, true);
    }
    if (isset($data_form[$field])) {
      $text_return = $data_form[$field];
      if (($text_return == '' || $text_return === 'undefined') && $text_return !== 0 && $text_return !== false) {
        $text_return = $text_default;
      }

      if (!empty($params)) {
        foreach ($params as $action) {
          switch ($action) {
            case 'trim':
              $text_return = trim($text_return);
              break;
            case 'clean':
              $text_return = htmlspecialchars($text_return, ENT_QUOTES, 'UTF-8');
              $text_return = str_replace(["\r", "\n"], ' ', $text_return);
              break;
            case 'ucfirst':
              $text_return = ucfirst(mb_strtolower($text_return, 'UTF-8'));
              break;
            case 'strtolower':
              $text_return = mb_strtolower(preg_replace('/\s+/', ' ', $text_return), 'UTF-8');
              break;
            case 'ucwords':
              $text_return = ucwords(mb_strtolower($text_return, 'UTF-8'));
              break;
            case 'strtoupper':
              $text_return = mb_strtoupper($text_return, 'UTF-8');
              break;
            case 'hash_pass':
              $text_return = hash_pass($text_return);
              break;
            case 'positive':
              $text_return = abs(floatval($text_return));
              break;
            case 'encrypt':
              $text_return = iD_encrypt($text_return);
              break;
            case 'notext':
              if ($text_return === '' || $text_return === null) {
                json_response(400, null, 'Debe completar el campo obligatorio ' . $field);
              }
              break;
            case 'notnull':
              if ($text_return === null) {
                json_response(400, null, 'No se permite campo nulo ' . $field);
              }
              break;
            case 'notfalse':
              if ($text_return === false) {
                json_response(400, null, 'No se permite campo nulo o falso ' . $field);
              }
              break;
            case 'boolean':
              $text_return = $text_return === true ? 1 : 0;
              break;
            case 'image':
              $text_return = str_replace(IMAGES, "", $text_return);
              break;
            case 'file':
              $text_return = str_replace(FILES, "", $text_return);
              break;
            case 'cellphone':
              $text_return = preg_replace('/[^0-9]/', '', $text_return);
              $text_return = ltrim($text_return, '0');
              $text_return = preg_replace('/^549|^59|^54|^9/', '', $text_return);
              break;
            case 'notascii':
              $text_return = mb_strtolower($text_return, 'UTF-8');
              $text_return = preg_replace('/[^a-z0-9\sáéíóúüñ]/u', '', $text_return);
              $text_return = preg_replace('/\s+/', ' ', $text_return);
              break;
            case 'date':
              if (!check_date($text_return)) {
                json_response(400, null, 'La fecha ingresada no es válida');
              }
              break;
          }
        }
      }
    } else {
      if (in_array('notnull', $params)) {
        if ($text_default != '') {
          $text_return = $text_default;
        } else {
          json_response(400, null, 'No se encontró variable ' . $field);
        }
      }
      $text_return = NULL;
    }
  }

  return $text_return;
}
/**  Devuelve una cadena json para enviar al front 
 *
 * @param array $json
 * @param boolean $die
 * @return void
 */
function json_response($status = 200, $data = null, $msg = '', $die = true)
{
  if (empty($msg) || $msg == '') {
    switch ($status) {
      case 200:
        $msg = 'OK';
        break;
      case 201:
        $msg = 'Created';
        break;
      case 400:
        $msg = 'Invalid request';
        break;
      case 403:
        $msg = 'Access denied';
        break;
      case 404:
        $msg = 'Not found';
        break;
      case 500:
        $msg = 'Internal Server Error';
        break;
      case 550:
        $msg = 'Permission denied';
        break;
      default:
        break;
    }
  }

  $json =
    [
      'status' => $status,
      'error'  => false,
      'msg'    => $msg,
      'data'   => $data
    ];

  if (in_array($status, [400, 403, 404, 405, 500])) {
    $json['error'] = true;
  }

  header('Access-Control-Allow-Origin: *');
  header('Content-type: application/json;charset=utf-8');

  echo json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK); // JSON_PRETTY_PRINT, JSON_FORCE_OBJECT

  if ($die) {
    die();
  }

  return true;
}
/** Loggea un registro en un archivo de logs del sistema, usado para debugging ['debug', 'import', 'info', 'success', 'warning', 'error']
 *
 * @param string $message
 * @param string $type
 * @param boolean $output
 * @return mixed
 */
function logger($message, $type = 'debug', $output = false)
{
  $types = ['debug', 'import', 'info', 'success', 'warning', 'error'];

  if (!in_array($type, $types)) {
    $type = 'debug';
  }

  $now_time = date("d-m-Y H:i:s");

  $message = "[" . strtoupper($type) . "] $now_time - $message";

  if (!$fh = fopen(LOGS . "_log.log", 'a')) {
    error_log(sprintf('No se pudo abrir el archivo de logs %s', LOGS . '_log.log'));
    return false;
  }

  fwrite($fh, "$message\n");
  fclose($fh);
  if ($output) {
    print "$message\n";
  }

  return true;
}
/** Devuelve la IP del cliente actual
 *
 * @return void
 */
function get_user_ip()
{
  $ipaddress = '';
  if (getenv('HTTP_CLIENT_IP'))
    $ipaddress = getenv('HTTP_CLIENT_IP');
  else if (getenv('HTTP_X_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
  else if (getenv('HTTP_X_FORWARDED'))
    $ipaddress = getenv('HTTP_X_FORWARDED');
  else if (getenv('HTTP_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_FORWARDED_FOR');
  else if (getenv('HTTP_FORWARDED'))
    $ipaddress = getenv('HTTP_FORWARDED');
  else if (getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
  else
    $ipaddress = 'UNKNOWN';
  return $ipaddress;
}
/** Devuelve el navegador el agente completo
 *
 * @return void
 */
function get_user_agent()
{
  $user_agent = (isset($_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : NULL);
  return $user_agent;
}
/** Devuelve el navegador del cliente
 *
 * @return void
 */
function get_user_browser()
{
  $user_agent     = (isset($_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : NULL);

  $browser        = "Unknown Browser";

  $browser_array  = array(
    '/msie/i'      => 'Internet Explorer',
    '/firefox/i'   => 'Firefox',
    '/safari/i'    => 'Safari',
    '/chrome/i'    => 'Chrome',
    '/edge/i'      => 'Edge',
    '/opera/i'     => 'Opera',
    '/netscape/i'  => 'Netscape',
    '/maxthon/i'   => 'Maxthon',
    '/konqueror/i' => 'Konqueror',
    '/mobile/i'    => 'Handheld Browser'
  );

  foreach ($browser_array as $regex => $value) {
    if (preg_match($regex, $user_agent)) {
      $browser = $value;
    }
  }

  return $browser;
}
/** Muestra formateada en pantalla una variable
 *
 * @param mixed $data
 * @return void
 */
function debug($data, $die = false)
{
  echo '<pre>';
  if (is_array($data) || is_object($data)) {
    print_r($data);
  } else {
    echo $data;
  }
  echo '</pre>';

  if ($die == true) {
    die();
  }
}
/** Inserta campos de comprobacion en un formulario
 *
 * @return string
 */
function insert_inputs()
{
  if (isset($_POST['redirect_to'])) {
    $location = $_POST['redirect_to'];
  } else if (isset($_GET['redirect_to'])) {
    $location = $_GET['redirect_to'];
  } else {
    $location = CUR_PAGE;
  }

  $output = '<input type="hidden" name="redirect_to" value="' . $location . '">';
  $output .= '<input type="hidden" name="timecheck" value="' . time() . '">';
  $output .= '<input type="hidden" name="csrf" value="' . CSRF_TOKEN . '">';

  return $output;
}
/** Comprueba si la peticion por parte del frontend viene con el CSRF (siempre debe venir en la raiz del $_POST)
 *
 * @return string
 */
function check_csrf($csrf = null)
{
  (!isset($_POST['csrf'])) ? $csrf = null : $csrf = $_POST['csrf'];

  if (!Csrf::validate($csrf)) {
    destroy(cookie_iD);
    logger('Sin CSRF requerido. IP: ' . get_user_ip(), 'warning');
    json_response(550, null, 'Token de sesion invalido.Token: ' . $csrf);
  }
}
# Funciones propias de FlexiMVC
/** Devuelve password HASHADO
 *
 * @return string
 */
function hash_pass($pass)
{
  return password_hash($pass . AUTH_SALT, PASSWORD_DEFAULT);
}

/**  Genera codigos aleatorios con el patron uniqid() de PHP
 *
 * @return string
 */
function generate_ref()
{
  return uniqid();
}
/** Genera codigos aleatorios segun paramentros enviados
 *
 * @return string
 */
function generate_code($length, $upper = false)
{
  if (!$upper) {
    $chars = "abcdfghjkmnpqrstvwxyzABCDFGHJKLMNPQRSTVWXYZ0123456789";
  } else {
    $chars = "ABCDFGHJKLMNPQRSTVWXYZ0123456789";
  }

  $chars_length = strlen($chars);
  $random_string = '';
  $randon_number = 0;

  $all = str_split($chars);
  for ($i = 0; $i < $length; $i++) {
    $randon_number = rand(0, $chars_length - 1);
    $random_string .= $all[$randon_number];
  }

  return $random_string;
}
/** A partir de los parametros que desea enviar en la consulta SQL genera los bind de cada campo campo=:valor
 *
 * @return string
 */
function generate_bind($sql, $params = [])
{
  $bind_string = "";
  foreach (array_keys($params) as $value) {
    $bind_string .= $value . "=:" . $value . ",";
  }
  $bind_string = rtrim($bind_string, ',');
  return $sql . $bind_string;
}
/** Arma la ruta de archivos para armar HTML
 *
 * @return string
 */
function get_include($module, $file)
{
  return MODULES_PHP . 'site' . DS . '_includes' . DS . $file . '.php';
}
/** Arma la ruta de archivos VUE
 *
 * @return string
 */
function get_vue($module, $file, $seccion = CONTROLLER)
{
  return MODULES . $module . '/views/' .   $seccion . '/' . $file . '.js?v=' . rand();
}
function _include($module, $file)
{
  return MODULES_PHP . $module . DS . '_includes' . DS . $file . '.php';
}
function vue_js($module, $file, $folder = null)
{
  if ($folder != null) {
    return MODULES . $module . "/views/$folder/" .  $file . 'Vue.js?v=' . rand();
  } else {
    return MODULES . $module . '/views/' .  $file . 'Vue.js?v=' . rand();
  }
}
/** Comprueba si el sitio esta en mantenimiento
 *
 */
function check_status()
{
  if (site_maintenance) {
    Redirect::to('pages/maintenance');
  }
}
/** Coloca en la pagina los metas disponibles para que el front end pueda interactuar
 *
 */
function set_meta($custom_metas = [])
{
  $meta                   = '<meta name="%s" content="%s" />' . "\n";
  // Convertir objeto a array si es necesario
  if (is_object($custom_metas)) {
    $custom_metas = get_object_vars($custom_metas);
  }

  // Metadatos predeterminados
  $metas['csrf']          = CSRF_TOKEN;
  $metas['uri']           = URL;
  $metas['cur_page']      = URL . CONTROLLER;
  $metas['is_local']      = IS_LOCAL;
  $metas['basepath']      = BASEPATH;
  $metas['port']          = PORT;
  $metas['request_uri']   = REQUEST_URI;
  $metas['assets']        = ASSETS;
  $metas['urlimages']     = IMAGES;
  $metas['urlupload']     = URL . 'site/upload';
  $metas['php_version']   = phpversion();
  $metas['date_now']      = date_now();
  $metas['date_js']       = date_js();
  $metas['date_full']     = date_full();
  $metas['date_first']    = date_first();
  $metas['date_last']     = date_last();
  $metas['date_month']    = date_month();
  $metas['date_year']     = date_year();

  $metas = array_merge($metas, $custom_metas);
  foreach ($metas as $item => $value) {
    echo sprintf($meta, $item, $value);
  }
}
/** Trae los datos del usuario loggeado. Si no esta iniciado lo envie a la pagina $redirect.
 *
 * @return object
 */
function get_user($var, $redirect = null, $json_ = false)
{
  if (!Auth::validate($var)) {
    if ($redirect == null) {
      $redirect = DEFAULT_HOME;
    }

    if ($json_ == true) {
      json_response(550, $redirect, 'Debe iniciar sesion para utilizar recurso');
    }

    Redirect::to($redirect);
  } else {
    return to_object(Auth::get_user($var));
  }
}
/** Funcion para verificar el password ingresado y el que posee el sistema
 *
 * @return boolean
 */
function check_pass($pass_entered, $pass_user)
{
  if (!password_verify($pass_entered . AUTH_SALT, $pass_user)) {
    return false;
  } else {
    return true;
  }
}
/** Verifica si el sitio esta en produccion
 *
 */
function check_production()
{
  if (site_production) {
    json_response(404, null, 'No permitido en produccion');
  }
}
/** Funcion para encriptar un string con openssl y la KEY definida en AUTH_SALT
 *
 * @return string
 */
function iD_encrypt($string)
{
  $string = openssl_encrypt($string, AES, KEY);
  return rtrim(base64_encode($string), '=');
}
/** Funcion para desencriptar un string con openssl y la KEY definida en AUTH_SALT
 *
 * @return string
 */
function iD_decrypt($string)
{
  $string = base64_decode($string);
  return openssl_decrypt($string, AES, KEY);
}
/** Devuelve la hora actual con el formato H:i
 *
 * @return string
 */
function time_now()
{
  return date('H:i');
}
/** Devuelve la fecha actual con el formato Y-m-d H:i:s
 *
 * @return string
 */
function now()
{
  return date('Y-m-d H:i:s');
}
/** Devuelve la fecha actual con el formato d/m/Y
 *
 * @return string
 */
function date_now()
{
  return date('d/m/Y');
}
/** Devuelve la fecha actual con el formato Y-m-d
 *
 * @return string
 */
function date_js($date = null)
{
  if ($date == null) {
    $date = now();
  }
  $timestamp    = strtotime($date);
  $date_output  = date("Y-m-d", $timestamp);

  return $date_output;
}
function to_localdatetime($isoDateStr)
{
  $local_time = date_default_timezone_get();

  return (new DateTime($isoDateStr, new DateTimeZone('UTC')))
    ->setTimezone(new DateTimeZone($local_time))
    ->format('Y-m-d H:i:s');
}
/** Devuelve la fecha actual con el formato Ymd
 *
 * @return string
 */
function date_cbte($date = null)
{
  if (!$date) return date('Ymd'); // Si no hay fecha, retorna la actual
  foreach (['Y-m-d H:i:s', 'Y-m-d', 'd/m/Y H:i', 'd/m/Y', 'm/d/Y'] as $format) {
    if ($date_time = DateTime::createFromFormat($format, $date)) {
      return $date_time->format('Ymd');
    }
  }
  return null; // Si no se pudo convertir, retorna null
}
function date_arg($date = null)
{
  if ($date == null) {
    $date = now();
  }
  $timestamp    = strtotime($date);
  $date_output  = date("d/m/Y", $timestamp);

  return $date_output;
}
/**  Suma o resta dias para calcular las fechas
 *
 * @return string
 */
function now_interval($time = null, $date = null)
{
  if ($date == null) {
    $date = date('Y-m-d');
  }

  if ($time != null) {
    $times              = strtotime($date);
    $one_day            = 86400;
    $interval_days      = $one_day * abs($time);
    if (substr($time, 0, 1) == '+') {
      $times += $interval_days;
    } else {
      $times -= $interval_days;
    }
    $date               = date("Y-m-d", $times);
  }

  return $date;
}
/** Formatea la fecha para mostrarla completa con dia y mes textual
 *
 * @return string
 */
function date_full($date = null)
{
  if ($date == null) {
    $date     = date('Y-m-d');
  }

  $time       = strtotime($date);
  $day_week   = date("w", $time);
  $day        = date("d", $time);
  $month      = date("n", $time);
  $year       = date("Y", $time);

  $days_weeks = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
  $months     = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

  return $days_weeks[$day_week] . " " . $day . " de " . $months[$month - 1] . " del " . $year;
}
/** Remplaza caracteres especiales con compatibles con la URL
 *
 * @return string
 */
function replace_chars($string, $underscore = false)
{
  $string         = strtolower($string);
  /* Caracteres Especiales */
  $special_chars  = array("º", "ª", "\\", "!", "\"", "·", "#", "$", "~", "€", "%", "¬", "&", "/", "(", ")", "=", "?", "\'", "¿", "¡", ",", "`", "^", "[", "+", "*", "]", "{", "}", ".", ":", "-", "_");
  $string         = str_replace($special_chars, "", $string);
  /* Caracteres ñ */
  $special_chars  = array("ñ");
  $string         = str_replace($special_chars, "ni", $string);
  /* Letra espacio por guion */

  $separator      = $underscore != true ? '-' : '_';

  $special_chars  = array(" ");
  $string         = str_replace($special_chars, $separator, $string);

  return $string;
}
/** Genera un nombre de usuario a partir del nombre y apellido
 *
 * @return string
 */
function generate_username($name, $lastaname)
{
  $option   = "/\b(\w)[^\s]*\s*/m";
  $caracter = '$1';

  $result   = preg_replace($option, $caracter, $name);
  $user_end = $result . $lastaname;

  $user_end = strtolower($user_end);
  return $user_end;
}
/** Formatea un valor numerico si se desea mostrar con puntos los miles y con cuantos decimales
 *
 * @return string
 */
function money($amount, $decimal, $format = false)
{
  if (!$format) {
    $valor_final = number_format($amount, $decimal, ',', '');
  } else {
    $valor_final = number_format($amount, $decimal, ',', '.');
  }
  return $valor_final;
}
/** Genera un numero a partir de la fecha y hora, incluyendo segundos
 *
 * @return string
 */
function generate_nro()
{
  return date("YmdHis");
}
