<?php

class messagesController extends Controller
{
    public static $user;

    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
    }
    # Funciones para administrar los mensajes
    function message_delete()
    {
        try {
            $forms = check_form();

            $messages          = new messagesModel();
            $message_id        = get_form($forms, 'message_id', ['notnull']);
            $messages->message_condition($message_id, -1);

            $this->message_list('Mensaje elimino con exito');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function message_list($msg = null)
    {
        try {
            $messages                   = new messagesModel();
            $_send['message_listed']    = $messages->message_list();

            json_response(200, $_send, $msg);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function set_condition()
    {
        try {
            $forms = check_form();

            $messages           = new messagesModel();
            $message_id         = get_form($forms, 'message_id', ['notnull']);
            $message_condition  = get_form($forms, 'message_condition', ['notnull']);

            # Check estado
            switch ($message_condition) {
                case 1:
                case '1':
                    $message_condition = 0;
                    break;
                case 0:
                case '0':
                    $message_condition = 1;
                    break;
            }
            $messages->message_condition($message_id, $message_condition);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
