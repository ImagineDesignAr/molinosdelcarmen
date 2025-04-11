<?php
# Controlador de modulo GESTION

class gestionController extends Controller
{
    public $var         = var_gestion;
    public $controller  = 'gestion';
    public $THEME       = STYLES . 'default/';
    public $STYLE       = STYLES . 'admin/';
    public $FONTS       = STYLES . 'fonts/';

    public static $user;
    function __construct()
    {
        check_status();

        # Ejecuta constructor padre
        parent::__construct();

        if (!management_enabled) {
            # Revisar si el modulo esta habilitado
            Redirect::to('pages/e404');
        }
        if (management_maintenance) {
            # Revisar si el modulo esta en mantenimiento
            Redirect::to('pages/maintenance');
        }
        # Configuracion inicial
        $this->site['title']            = title_account;
        $this->site['name']             = title_account;
        $this->site['author']           = title_account;
        $this->site['description']      = '';
        $this->site['logo_light']       = IMAGES . 'logo_blanco_md.png';
        $this->site['logo_dark']        = IMAGES . 'logo_blanco_md.png';
        $this->site['logo_local']       = IMAGES . 'logo_negro_vr.png';
        $this->site['about']            = 'https://imaginedesign.ar';
        $this->site['favicon']          = favicon_account;
        $this->site['home']             = URL . $this->controller . '/index';
        $this->site['reset']            = URL . $this->controller . '/reset';
        $this->site['lock']             = URL . $this->controller . '/lock';
        $this->site['locked']           = URL . $this->controller . '/locked';
        $this->site['logout']           = URL . $this->controller . '/logout';
        $this->site['unlock']           = URL . $this->controller . '/unlock';
        $this->site['login']            = URL . $this->controller . '/login';
        $this->site['html']             = '';
        $this->site['body_class']       = '';
        $this->site['body_style']       = '';
        # Modulo dependiente de enlaces 
        $this->site['controller']       = $this->controller;
        # Enlaces personalizados
        //
        //
        //
        # Style Defaults
        $this->site['head'][]           = $this->THEME . 'css/bootstrap.min.css';
        $this->site['head'][]           = $this->THEME . 'css/app.min.css';
        $this->site['head'][]           = $this->THEME . 'css/icons.min.css';
        $this->site['head'][]           = $this->THEME . 'css/custom.min.css';
        # Style Admin
        $this->site['head'][]           = $this->STYLE . 'style.min.css';
        $this->site['head'][]           = $this->STYLE . 'tabler.css';
        $this->site['head'][]           = $this->STYLE . 'spinner.css';
        # Style Custom
        //$this->site['head'][]           = $this->FONTS . 'fonts.css';
        # JScript Admin
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2.min.js';
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2-toast.js';
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2.css';
        $this->site['head'][]           = LIBS . 'toastify-js/toastify.js';
        $this->site['head'][]           = LIBS . 'vue/vue.js';
        $this->site['head'][]           = LIBS . 'axios/axios.min.js';
        $this->site['head'][]           = LIBS . 'fleximvc/app.js';
        $this->site['footer'][]         = LIBS . 'bootstrap/js/bootstrap.bundle.min.js';
        $this->site['footer'][]         = LIBS . 'node-waves/waves.min.js';
        $this->site['footer'][]         = LIBS . 'feather-icons/feather.min.js';
        $this->site['footer'][]         = LIBS . 'simplebar/simplebar.min.js';
        $this->site['footer'][]         = $this->THEME . 'js/layout.js';
        $this->site['footer'][]         = $this->THEME . 'js/app.js';

    }
    # ===== Funciones Sistema 
    private function user_session()
    {
        self::$user = get_user($this->var, $this->site['login']);
    }
    # Funcion para verificar que el usuario cambie la contraseña por defecto (DNI)
    private function compare_pass()
    {
        try {
            if (password_verify(self::$user->user . AUTH_SALT, self::$user->pass)) {
                Flasher::new('Debe crear una nueva contraseña', 'danger');
                $this->reset();
            }
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Revisa que el usuario tenga acceso al methodo solicitado
    private function page_access()
    {
        $pages  = get_pages(self::$user->profile);

        if (METHOD === 'index') {
            Redirect::to($this->controller . $pages['index']);
        }

        if (!in_array(METHOD, $pages['pages'])) {
            Flasher::new('No posee acceso al recurso solicitado ' . METHOD, 'danger');
            Redirect::to($this->controller . $pages['index']);
        }
    }
    /* Pagina principal del perfil iniciado */
    function index()
    {
        $this->user_session();
        $this->page_access();
        debug('Detente! No me gusta juga asi', null, true);
    }
    /** Funcion para bloquear sesion de usuario. */
    function lock()
    {
        $_SESSION[$this->var]['user']['block']  = true;
        Redirect::to($this->site['locked']);
    }
    /** Funcion para desbloquear sesion de usuario. */
    function unlock()
    {
        $this->user_session();
        $pass = get_form($_POST, 'userpassword');

        try {
            if (password_verify($pass . AUTH_SALT, self::$user->pass)) {
                $_SESSION[$this->var]['user']['block']   = false;
                Redirect::to($this->site['home']);
            } else {
                logger('Ingreso de contraseña erroneo en login de usuario. Modulo Gestion. IP: ' . get_user_ip(), 'error');
                Flasher::new('La contraseña no es valida.', 'danger');
                Redirect::to($this->site['locked']);
            }
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    /** Funcion para cambiar pass de usuario. */
    function change_pass()
    {
        $this->user_session();
        # Primero comprobamos que los valores no vengan nulos
        $pass_old       = get_form($_POST, 'pass_old');
        $pass_new       = get_form($_POST, 'pass_new');
        $pass_renew     = get_form($_POST, 'pass_renew');

        if ($pass_old == '' || $pass_new == '' || $pass_renew == '') {
            Flasher::new('Las contraseñas no pueden ser nulas');
            Redirect::to($this->site['reset']);
        }
        # Comprobar si la clave nueva es identica a la repeticion
        if ($pass_new != $pass_renew) {
            Flasher::new('Las contraseñas ingresadas no coinciden');
            Redirect::to($this->site['reset']);
        }
        # Comprobar si la clave ingresada sea valida
        if (check_pass($pass_old, self::$user->pass)) {
            try {
                person_pass(self::$user->person_id, $pass_new);

                Flasher::new('La contraseña a sido actualizada.');
                Auth::logout($this->var);
                Redirect::to($this->site['login']);
            } catch (Exception $e) {
                json_response(404, null, $e->getMessage());
            }
        } else {
            Flasher::new('Las contraseña ingresada no es correcta');
            Redirect::to($this->site['reset']);
        }
    }
    /** Link para cerrar sesion. */
    function logout()
    {
        if (Auth::logout($this->var)) {
            Redirect::to($this->site['login']);
        }
    }
    /** Funcion para bloquear sesion de usuario. */
    function locked()
    {
        $this->user_session();
        if (self::$user->block == true) {
            $this->site['logo']     = IMAGES . 'logo_verde_md.png';
            $this->site['title']    = 'Gestion Bloqueda';
            $this->site['user']     = self::$user;
            $this->site['ref']      = 'gestion';
            $this->site['action']   = $this->site['unlock'];
            View3::render('admin', 'locked', $this->site);
        }
    }
    /* Pagina para retear clave por parte del usuario */
    function reset()
    {
        $this->user_session();
        $this->site['logo']     = IMAGES . 'logo_verde_md.png';
        $this->site['title']    = 'Cambiar Contraseña';
        $this->site['action']   = URL . 'gestion/change_pass';

        View3::render('admin', 'reset', $this->site);
    }
    /* Pagina ACERCA DE */
    function about()
    {
        Redirect::to($this->site['about']);
    }
    /* Variables globales dentro de la sesion de usuario */
    private function _SESSION()
    {
        $_SESSION['defaults']['category_physical']  = 'all';
        $_SESSION['defaults']['brand_physical']     = 'all';
    }
    /* Funcion para iniciar el aplicativo. */
    function access()
    {
        check_csrf();

        try {
            $var                        = var_gestion;
            $document                   = get_form($_POST, 'user', ['trim', 'strtolower']);
            $password                   = get_form($_POST, 'pass');
            $store_id                   = get_form($_POST, 'store', ['strtolower']);
            $profile                    = get_form($_POST, 'profile', ['notnull']);

            $person                     = new staffModel();
            $data_person                = person_one($document);

            if ($data_person['person_condition'] == 0) {
                log_error('Persona no admitida para el uso del sistema. ' . $document, 'gestion');
                die();
            }
            if ($data_person['person_condition'] == 2) {
                log_error('Persona inhabilitada para uso del sistema. ' . $document, 'gestion');
                die();
            }
            if (!password_verify($password . AUTH_SALT, $data_person['person_pass'])) {
                log_error('Error en las credenciales. ' . $document, 'gestion');
                die();
            }

            $person->person_access_bound    = $data_person['person_id'];
            $person->person_access_store    = $store_id;
            $person->person_access_profile  = $profile;

            # Comprobamos que la persona esta habilitada para operar en sucursal
            $data_profile = $person->access_check();
            if (!$data_profile) {
                log_error('Perfil no habilitado en sucursal seleccionada. ' . $document, 'gestion');
                die();
            }
            # Comprobamos que el profile sea valido
            $data_profile = $person->profile_one();
            if ($data_profile === []) {
                log_error('Perfil no valido. ' . $document, 'gestion');
                die();
            }

            # Cargamos los datos de la sucursal
            $store                          = store_one($store_id);
            $printer_store                  = printer_store($store_id);

            $user_data['person_id']         = $data_person['person_id'];
            $user_data['person_name']       = ucwords($data_person['person_lastname'] . ' ' . $data_person['person_name']);
            $user_data['person_picture']    = IMAGES . $data_person['person_picture'];
            $user_data['profile']           = $data_profile['person_profile_name'];
            $user_data['profiles_name']     = ucwords($data_profile['person_profile_text']);
            $user_data['user']              = $data_person['person_document'];
            $user_data['pass']              = $data_person['person_pass'];
            $user_data['store_id']          = $store_id;
            $user_data['store_name']        = ucwords($store['store_name']);
            $user_data['store_cash']        = ($store['store_cash'] ?? 0);
            $user_data['printer_id']        = ($printer_store['store_printer_id'] ?? 0);
            $user_data['block']             = false;
            $this->_SESSION();

            # Loggear al usuario
            lastlogin($data_person['person_id']);
            Auth::login($var, $data_person['person_id'], $user_data);
            Redirect::to($this->site['home']);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    /* Inicio de sesion solo con perfil sucursales */
    function login($profile = null)
    {
        # Si el usuario ya esta iniciado lo envia a su index
        if (Auth::validate($this->var)) {
            Redirect::to($this->site['home']);
        }
        # Configuracion Personalizada
        $this->site['title']            = 'Iniciar Sesion';
        $this->site['ref']              = 'login';
        $this->site['logo']             = IMAGES . 'logo_verde_md.png';
        $this->site['stores']           = store('store');
        $this->site['profiles']         = profiles('store');
        $this->site['insert_inputs']    = insert_inputs();
        $this->site['action']           = URL . 'gestion/access';
        View3::render('admin', 'login', $this->site);
    }
    /* Inicio de sesion perfil administrador */
    function administrador($profile = null)
    {
        # Si el usuario ya esta iniciado lo envia a su index
        if (Auth::validate($this->var)) {
            Redirect::to($this->site['home']);
        }
        # Configuracion Personalizada
        $this->site['title']            = 'Iniciar Sesion';
        $this->site['ref']              = 'login';
        $this->site['logo']             = IMAGES . 'logo_verde_md.png';
        $this->site['stores']           = store();
        $this->site['insert_inputs']    = insert_inputs();
        $this->site['action']           = URL . 'gestion/access';
        $this->site['profiles']         = '<option value="1">Administrador</option>';
        View3::render('admin', 'login', $this->site);
    }
    ##############################################################################################################
    function mensajes()
    {
        $this->user_session();
        $this->page_access();
        $this->compare_pass();
        $this->locked();

        $this->site['user']     = self::$user;
        $this->site['title']    = 'Pagina Mensajes';
        $this->site['ref']      = 'messages';

        View3::render('messages', 'mensajes', $this->site, true);
    }
    ##############################################################################################################
    function blogs()
    {
        $this->user_session();
        $this->page_access();
        $this->compare_pass();
        $this->locked();

        $this->site['user']     = self::$user;
        $this->site['title']    = 'Pagina Blogs';
        $this->site['ref']      = 'blogs';

        View3::render('blogs', 'blog', $this->site, true);
    }
    ##############################################################################################################
    function articulos()
    {
        $this->user_session();
        $this->page_access();
        $this->compare_pass();
        $this->locked();

        $this->site['user']     = self::$user;
        $this->site['title']    = 'Pagina Articulos';
        $this->site['ref']      = 'articulos';

        View3::render('articles_physical', 'articulos', $this->site, true);
    }
    /** ================== MODULO STAFF (requiere _personController y _personModel) ==================  */
    function personal() # Version Nueva
    {
        $this->user_session();
        $this->locked();
        $this->compare_pass();
        $this->page_access();

        $this->site['user']     = self::$user;
        $this->site['title']    = 'Pagina Personal';
        $this->site['ref']      = 'personal';

        View3::render('staff', 'personal', $this->site, true);
    }
    ##############################################################################################################
    function sucursales() # Version Nueva
    {
        $this->user_session();
        $this->locked();
        $this->compare_pass();
        $this->page_access();

        $this->site['user']     = self::$user;
        $this->site['title']    = 'Pagina Sucursales';
        $this->site['ref']      = 'sucursales';

        View3::render('stores', 'stores', $this->site, true);
    }
    function sucursal() # Version Nueva
    {
        $this->user_session();
        $this->locked();
        $this->compare_pass();
        $this->page_access();

        $this->site['user']     = self::$user;
        $this->site['title']    = 'Pagina Sucursal';
        $this->site['ref']      = 'sucursal';

        View3::render('stores', 'store', $this->site, true);
    }
    # =============== fin vistas html ======================
    # ================ Modo Desarrollo ===============
    public function variables() {}
    public function flasher()
    {
        Flasher::new('Flasher de prueba', 'success');
    }
}
