<?php

class blogsController extends Controller
{
    public static $user;

    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
    }
    # Funciones para administrar las noticias
    function blog_new()
    {
        try {

            $blog_new['blog_id']        = '';
            $blog_new['blog_date']      = date_js();
            $blog_new['blog_picture']   = IMAGES . '_nodisponible.jpg';
            $blog_new['blog_title']     = '';
            $blog_new['blog_summary']   = '';
            $blog_new['blog_content']   = '';
            $blog_new['blog_link']      = '';

            $_send['blog_form'] =  $blog_new;

            json_response(200, $_send, 'Datos por defecto');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function blog_add()
    {
        try {
            $forms = check_form();

            $blogs                  = new blogsModel();
            $blogs->blog_date       = get_form($forms, 'blog_date', ['date']);
            $blogs->blog_picture    = get_form($forms, 'blog_picture', ['notnull', 'image']);
            $blogs->blog_title      = get_form($forms, 'blog_title', ['notnull']);
            $blogs->blog_summary    = get_form($forms, 'blog_summary', ['notnull']);
            $blogs->blog_content    = get_form($forms, 'blog_content', ['notnull']);
            $blogs->blog_link       = get_form($forms, 'blog_link', []);
            $blogs->blog_person     = self::$user->person_id;
            $blogs->blog_store      = self::$user->store_id;
            $blogs->blog_condition  = 1;
            $blogs->blog_add();

            $this->blog_list('Blog creado con exito');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function blog_view()
    {
        try {
            $forms = check_form();

            $blogs                  = new blogsModel();
            $blogs->blog_id         = get_form($forms, 'blog_id', ['notnull']);
            $blog_data              = $blogs->blog_view();

            if ($blog_data != []) {
                $blog_data['blog_picture']  = IMAGES . $blog_data['blog_picture'];
            }
            $_send['blog_form']     =  $blog_data;

            json_response(200, $_send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function blog_update()
    {
        try {
            $forms = check_form();

            $blogs                  = new blogsModel();
            $blogs->blog_id         = get_form($forms, 'blog_id', ['notnull']);
            $blogs->blog_date       = get_form($forms, 'blog_date', ['date']);
            $blogs->blog_picture    = get_form($forms, 'blog_picture', ['notnull', 'image']);
            $blogs->blog_title      = get_form($forms, 'blog_title', ['notnull']);
            $blogs->blog_summary    = get_form($forms, 'blog_summary', ['notnull']);
            $blogs->blog_content    = get_form($forms, 'blog_content', ['notnull']);
            $blogs->blog_link       = get_form($forms, 'blog_link', []);
            $blogs->blog_condition  = get_form($forms, 'blog_condition', ['notnull']);
            $blogs->blog_update();

            $this->blog_list('Blog editado con exito');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function blog_delete()
    {
        try {
            $forms      = check_form();

            $blogs      = new blogsModel();
            $blog_id    = get_form($forms, 'blog_id', ['notnull']);
            $blogs->blog_condition($blog_id, -1);

            $this->blog_list('Blog eliminada con exito');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function blog_list($msg = null)
    {
        try {
            $blogs      = new blogsModel();
            $blog_data  = $blogs->blog_list();
            if ($blog_data != []) {
                foreach ($blog_data as &$blog) {
                    $blog['blog_date']      = date_arg($blog['blog_date']);
                    $blog['blog_picture']   = IMAGES . $blog['blog_picture'];
                    $blog['keywords']       = generate_keywords([$blog['blog_title']]);
                }
                unset($_vl); // Evita problemas con la referencia en el último elemento
            }
            $_send['blog_listed'] = $blog_data;

            json_response(200, $_send, $msg);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function last_3($msg = null)
    {
        try {
            $blogs      = new blogsModel();
            $blog_data  = $blogs->blog_last3();
            if ($blog_data != []) {
                foreach ($blog_data as $_ix => $_vl) {
                    $blog_data[$_ix]['blog_picture'] = IMAGES . $_vl['blog_picture'];
                }
            }
            $_send['blog_listed'] = $blog_data;

            json_response(200, $_send, $msg);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function set_condition()
    {
        try {
            $forms = check_form();

            $blogs          = new blogsModel();
            $blog_id        = get_form($forms, 'blog_id', ['notnull']);
            $blog_condition = get_form($forms, 'blog_condition', ['notnull']);

            # Check estado
            switch ($blog_condition) {
                case 1:
                case '1':
                    $blog_condition = 0;
                    break;
                case 0:
                case '0':
                    $blog_condition = 1;
                    break;
            }
            $blogs->blog_condition($blog_id, $blog_condition);

            $this->blog_list('Blog creado con exito');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
