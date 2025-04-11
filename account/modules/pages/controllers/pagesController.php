<?php
# Controlador de modulo PAGES

class pagesController extends Controller
{
    public $controller  = 'pages';
    public $THEME       = STYLES . 'default/';
    public $STYLE       = STYLES . 'admin/';

    function __construct()
    {
        parent::__construct();
        # Configuracion inicial
        $this->site['title']            = 'AiSushi';
        $this->site['name']             = 'AiSushi - by Mendoza';
        $this->site['logo']             = IMAGES . 'logo.png';
        $this->site['favicon']          = IMAGES . '_logocorazon.png';
        $this->site['logo_local']       = logo_green;
        $this->site['description']      = 'Framework ligero desarrollado por iD';
        $this->site['author']           = 'imagine Design';
        $this->site['about']            = 'https://imaginedesign.ar';
        # Modulo dependiente de enlaces 
        $this->site['controller']       = $this->controller;
        # Enlaces
        $this->site['index']            = URL . 'links/index';
        $this->site['about']            = 'https://imaginedesign.ar';

        $this->site['body_class']       = '';
        $this->site['body_style']       = '';

        $this->site['head'][]           = $this->THEME . 'css/bootstrap.min.css';
        $this->site['head'][]           = $this->THEME . 'css/app.min.css';
        $this->site['head'][]           = $this->THEME . 'css/icons.min.css';
        $this->site['head'][]           = $this->THEME . 'css/custom.min.css';
        $this->site['vue']              = [];
        $this->site['footer']           = [];
    }

    function index()
    {
        if (!isset($_POST['hook'])) {
            Redirect::to(DEFAULT_ERROR_CONTROLLER . DS . DEFAULT_ERROR_METHOD);
        }

        json_response(400, null, 'Recurso no encontrado');
    }

    function e404()
    {

        $this->site['logo']             = logo_account;
        $this->site['title']            = 'Error 404';
        $this->site['logo_page']        = logo_404;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['error']            = '404';
        $this->site['titulo']           = 'No se encontro la pagina que busca';
        $this->site['mensaje']          = 'Por favor revise la direccion ingresada.-';
        $this->site['buttomText']       = 'Home';
        $this->site['buttomRef']        = DEFAULT_HOME;

        View3::render('pages', 'error', $this->site);
    }

    function maintenance()
    {
        $this->site['logo']             = logo_account;
        $this->site['title']            = 'Pagina en Mantenimiento';
        $this->site['logo_page']        = logo_maintenance;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['error']            = '404';
        $this->site['titulo']           = 'Sitio en Mantenimiento';
        $this->site['mensaje']          = 'Estamos realizando mejoras a nuestra web para brindarte el mejor de los servicios.-';
        $this->site['buttomText']       = 'Contactar';
        $this->site['buttomRef']        = 'https://imaginedesign.ar';

        View3::render('pages', 'maintenance', $this->site);
    }

    function imagen()
    {
        check_production();

        $this->site['logo']             = logo_account;
        $this->site['title']            = 'Nombre de Imagenes';
        $this->site['logo_page']        = logo_widget;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['error']            = 'Nombre de Imagen';
        $this->site['titulo']           = 'Sitio en Mantenimiento';
        $this->site['mensaje']          = generate_code(6);
        $this->site['buttom_text']      = 'Recargar';
        $this->site['buttom_ref']       = 'imagen';

        View3::render('pages', 'imagen', $this->site);
    }

    function ref()
    {
        check_production();

        $ref = generate_ref();

        $this->site['logo']             = logo_account;
        $this->site['title']            = 'REFs aleatorias';
        $this->site['logo_page']        = logo_account;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['error']            = 'Referencias';
        $this->site['titulo']           = 'Referencias Aleatorias';
        $this->site['mensaje']          = 'Refer con MD5: ' . md5($ref) . '<br/>Refer uniqid: ' . $ref;
        $this->site['buttom_text']      = 'Recargar';
        $this->site['buttom_ref']       = 'ref';

        View3::render('pages', 'ref', $this->site);
    }

    function new_password($pass = '123456')
    {
        check_production();

        $this->site['logo']             = logo_account;
        $this->site['title']            = 'Nombre de Imagenes';
        $this->site['logo_page']        = logo_widget;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['error']            = 'Nuevo Password';
        $this->site['titulo']           = 'Hash de Passwords';
        $this->site['mensaje']          = 'Pass: ' . $pass . '</br> hash_pass: ' . hash_pass($pass);
        $this->site['buttom_text']      = 'Recargar';
        $this->site['buttom_ref']       = 'newpassword';

        View3::render('pages', 'newpassword', $this->site,);
    }

    function comingsoon()
    {
        $this->site['logo_page']        = logo_maintenance;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['titulo']           = 'muy pronto';
        $this->site['mensaje']          = 'Estamos preparando la web para que puedas disfrutar del servicio.-';
        $this->site['buttom_text']      = 'Contactar';
        $this->site['buttom_ref']       = 'https://imaginedesign.ar';

        View3::render('pages', 'comingsoon', $this->site);
    }
    function delete_image()
    {
        $this->site['logo_page']        = logo_maintenance;
        $this->site['bg_enterprise']    = bg_enterprise;
        $this->site['titulo']           = 'Administrado de Imagenes';
        $filenames                      = list_images();
        $table                          = '';
        foreach ($filenames as $filename) {
            $table .= '<tr>';
            $table .= '<td><img src="' . $filename['filename_image'] . '" alt="" class="rounded avatar-md"></td>';
            $table .= '<td>' . $filename['filename_image'] . '</td>';
            $table .= '<td>' . $filename['filename_volume'] . '</td>';
            $table .= '<td>' . $filename['filename_date'] . '</td>';
            $table .= '<td><a href="' . $filename['filename_href'] . '"><i class="ri-delete-bin-5-line"></i></a></td>';
            $table .= '</tr>';
        }

        $this->site['filenames']        = $table;

        View3::render('pages', 'delete_image', $this->site);
    }
}
