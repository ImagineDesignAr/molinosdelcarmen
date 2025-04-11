<?php

/**
 * Clase para crear sesiones seguras de usuarios. v 1.0
 */
class Auth
{
  private $logged = false;
  private $token  = null;
  private $id     = null;
  private $ssid   = null;
  private $user   = [];

  public function __construct()
  {
  }

  # Crear sesión de usuario
  public static function login($var, $user_id, $user_data = [])
  {
    $self    = new self();
    $session =
      [
        'logged' => true,
        'token'  => generate_code(32),
        'id'     => $user_id,
        'ssid'   => session_id(),
        'user'   => $user_data
      ];

    $_SESSION[$var] = $session;
    return true;
  }

  #  Validar la sesión del usuario
  public static function validate($var)
  {
    $self = new self();
    if (!isset($_SESSION[$var])) {
      return false;
    }

    # Valida la sesión
    return $_SESSION[$var]['logged'] === true && $_SESSION[$var]['ssid'] === session_id() && $_SESSION[$var]['token'] != null;
  }

  # Trae los datos del usuario loogueado 
  public static function get_user($var)
  {
    return $_SESSION[$var]['user'];
  }

  public static function user_object($var)
  {
    return to_object($_SESSION[$var]['user']);
  }

  public static function get_user_name($var)
  {
    return $_SESSION[$var]['user']['name'];
  }

  # Cerrar sesión del usuario
  public static function logout($var)
  {
    unset($_SESSION[$var]);
    return true;
  }

  public function __get($var)
  {
    if (!isset($this->{$var})) return false;
    return $this->{$var};
  }
}
