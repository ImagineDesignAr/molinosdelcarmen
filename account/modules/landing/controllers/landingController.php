<?php

class landingController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }
    # Global
    function page_data()
    {
        check_csrf();
        try {

            $physicals      = new articles_physicalModel();
            $physical_data  = $physicals->physical_front();
            if ($physical_data != []) {
                foreach ($physical_data as &$article) {
                    $article['physical_picture']    = IMAGES . $article['physical_picture'];
                    $article['keywords']            = generate_keywords([$article['physical_title'], $article['physical_presentation']]);
                    $article['condition_color']     = $article['article_condition_color'];
                }
            }
            $_send['article_listed'] = $physical_data;

            $blogs      = new blogsModel();
            $blog_data  = $blogs->blog_all();
            if ($blog_data != []) {
                foreach ($blog_data as &$blog) {
                    $blog['blog_picture'] = IMAGES . $blog['blog_picture'];
                    $blog['keywords']     = generate_keywords([$blog['blog_title']]);
                }
            }
            $_send['blog_listed'] = $blog_data;

            json_response(200, $_send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Vista HTML

    /* 
    function index()
    {
        $_SESSION['link_page'] = 'index';
        $this->view_page();
    } 
        */
    function index()
    {
        $this->front_landing();
        View3::render('landing', 'landing_es', $this->site);
    }
    function en()
    {
        $this->front_landing();
        View3::render('landing', 'landing_en', $this->site);
    }
    function send()
    {
        check_csrf();
        try {
            # Comprobar cantidad de mensajes enviados por una misma persona
            $_SESSION[var_landing]['message_count'] > 3 ? json_response(200, null, 'Excedio el limite de mensajes') : null;

            $forms      = check_form();
            $messages   = new messagesModel();

            # Cargamos todos los datos enviados por el usuario y preparamos todo para agregarlo a la base de datos
            $messages->message_name         = get_form($forms, 'message_name', ['notnull']);
            $messages->message_email        = get_form($forms, 'message_email', ['strtolower']);
            $messages->message_subject      = get_form($forms, 'message_subject', ['strtolower', 'notnull', 'notascii']);
            $messages->message_content      = get_form($forms, 'message_content', ['strtolower', 'notascii']);
            $messages->message_date         = now();
            $messages->message_condition    = 1;

            $messages->message_add();
            $_SESSION[var_landing]['message_count'] += 1;
            json_response(200, null, 'Mensaje enviado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function front_landing()
    {
        $CONTROLLER  = 'landing';
        $THEME       = STYLES . 'default/';
        $STYLE       = STYLES . 'landing/';
        # Configuracion inicial
        $this->site['title']            = title_account;
        $this->site['name']             = title_account;
        $this->site['author']           = title_account;
        $this->site['description']      = 'Somos una empresa familiar dedicada a la producción de harina de trigo con más de 15 años en el rubro.';
        $this->site['logo']             = IMAGES . 'logo_blanco_vr.png';
        $this->site['about']            = 'https://imaginedesign.ar';
        $this->site['favicon']          = IMAGES . 'favicon.png';
        # Modulo dependiente de enlaces 
        $this->site['controller']       = $CONTROLLER;
        # Enlaces
        $this->site['portada']          = IMAGES . 'portada_4.png';
        $this->site['fabrica']          = IMAGES . 'laempresa-1.jpg';
        $this->site['historia']         = IMAGES . 'laempresa-2.jpg';
        $this->site['objetivos']        = IMAGES . 'objetivos_1.png';
        $this->site['img_ar']           = FR_IMAGES . 'ar.svg';
        $this->site['img_us']           = FR_IMAGES . 'us.svg';
        $this->site['ref_ar']           = URL . 'ar';
        $this->site['ref_en']           = URL . 'en';
        $this->site['home']             = URL . 'index';
        $this->site['index']            = URL . 'landing/index';

        $this->site['body_class']       = '';
        $this->site['body_style']       = '';

        $this->site['head'][]           = $STYLE . 'css/bootstrap.min.css';
        $this->site['head'][]           = $THEME . 'css/app.min.css';
        $this->site['head'][]           = $THEME . 'css/icons.min.css';
        $this->site['head'][]           = $STYLE . 'css/pe-icon-7.css';
        $this->site['head'][]           = $STYLE . 'css/style.min.css';

        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2.min.js';
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2-toast.js';
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2.css';
        $this->site['head'][]           = LIBS . 'toastify-js/toastify.js';
        $this->site['head'][]           = LIBS . 'vue/vue.js';
        $this->site['head'][]           = LIBS . 'axios/axios.min.js';
        $this->site['head'][]           = LIBS . 'fleximvc/app.js';
        $this->site['head'][]           = LIBS . 'swiper/swiper-bundle.min.css';
        $this->site['head'][]           = LIBS . 'swiper/swiper-bundle.min.js';

        $this->site['footer'][]         = LIBS . 'bootstrap/js/bootstrap.bundle.min.js';
        $this->site['footer'][]         = LIBS . 'node-waves/waves.min.js';
        $this->site['footer'][]         = LIBS . 'feather-icons/feather.min.js';
        $this->site['footer'][]         = LIBS . 'simplebar/simplebar.min.js';
        $this->site['footer'][]         = $STYLE . 'js/app.init.js';
        # Contador de mensajes para evitar SPAM
        $_SESSION[var_landing]['message_count'] = $_SESSION['var_landing']['message_count'] ?? 0;
    }
    # Private


}
