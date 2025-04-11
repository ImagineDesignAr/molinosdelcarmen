<?php

/* Controlador base */

class nullController extends Controller

{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        /**
      200 OK
      201 Created
      300 Multiple Choices
      301 Moved Permanently
      302 Found
      304 Not Modified
      307 Temporary Redirect
      400 Bad Request
      401 Unauthorized
      403 Forbidden
      404 Not Found
      410 Gone
      500 Internal Server Error
      501 Not Implemented
      503 Service Unavailable
      550 Permission denied
         */
        json_response(403);
    }

    function add()
    {
        check_csrf();
        $forms = check_form();
        $user = get_user(var_gestion, 'gestion');

        try {

            $null                   = new nullModel();
            $null->null_condition   = get_form($forms, 'estado', ['notnull']);
            $null->add();

            json_response(200, null, 'Registro agregado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function update()
    {
        check_csrf();
        $forms = check_form();
        $user = get_user(var_gestion, 'gestion');

        try {
            $null                   = new nullModel();
            $null->null_condition   = get_form($forms, 'null_condition', ['notnull']);
            $null->null_id          = get_form($forms, 'null_id', ['notnull']);
            $null->update();

            json_response(200, null, 'Registro actualizado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function delete()
    {
        check_csrf();
        $forms  = check_form();
        $user   = get_user(var_gestion, 'gestion');

        try {
            $null           = new nullModel();
            $null->null_id  = get_form($forms, 'null_id', ['notnull']);

            /* Condicion que tiene que superar para eliminar el registro */
            $null->delete();

            json_response(200, null, 'Registro actualizado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }

    function one()
    {
        check_csrf();
        $forms  = check_form();
        $user   = get_user(var_gestion, 'gestion');

        try {
            $null           = new nullModel();
            $null->null_id  = get_form($forms, 'null_id', ['notnull']);
            $data_one       = $null->one();

            if ($data_one != []) {
                foreach ($data_one as $index => $row) {
                    $row[$index]['column'] = 'nuevo valor';
                }
            }

            json_response(200, $data_one);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function all()
    {
        check_csrf();
        $forms  = check_form();
        $user   = get_user(var_gestion, 'gestion');

        try {

            $null       = new nullModel();
            $data_null  = $null->all();

            if ($data_null != []) {
                foreach ($data_null as $ix_null => $vl_null) {
                    $vl_null[$ix_null]['column'] = 'nuevo valor';
                }
            }

            json_response(200, $data_null);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function status_change()
    {
        check_csrf();
        $forms  = check_form();
        $user   = get_user(var_gestion, 'gestion');

        try {
            $null       = new nullModel();
            $status_now = get_form($forms, 'null_condition', ['notnull', 'positive']);

            switch ($status_now) {
                case 0:
                    $status_new = 0;
                    break;
                case 1:
                    $status_new = 0;
                    break;
                default:
                    $status_new = $status_now;
                    break;
            }

            $null->null_condition  = $status_new;
            $null->status_change($status_new, 'null_condition');

            json_response(200, null, 'Estado del registro actualizado');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
