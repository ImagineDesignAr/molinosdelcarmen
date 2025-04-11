<?php

class staffController extends Controller
{
    public static $user;
    public static $msg = null;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
    }
    /* ================== STAFF - Requiere tabla persons ================== */
    function staff_pass()
    {
        $forms                          = check_form();
        try {
            $person_id                  = get_form($forms, 'person_id', ['notnull']);
            $person_document            = get_form($forms, 'person_document', ['notnull']);

            person_pass($person_id, $person_document);

            json_response(200, null, 'Contraseña reseteada.');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function staff_store()
    {
        try {
            $person             = new staffModel();
            $person->store_id   = self::$user->store_id;

            $data_profile       = $person->profile_list('all');
            $data_staff         = $person->staff_all();

            if ($data_staff != []) {
                foreach ($data_staff as $index => $row) {
                    $data_staff[$index]['person_name']      = $row['person_name'];
                    $data_staff[$index]['person_lastname']  = $row['person_lastname'];
                    $data_staff[$index]['person_picture']   = IMAGES . $row['person_picture'];
                    $data_staff[$index]['profiles_enabled'] = to_array($row['profiles_enabled']);
                    $data_staff[$index]['keywords']         = generate_keywords([$row['person_name'], $row['person_lastname'], $row['person_document'], $row['person_cellphone']]);
                    $data_staff[$index]['spinner']          = false;
                }
            }
            $send_data['staff_listed']  = $data_staff;
            $send_data['staff_profile'] = $data_profile;

            json_response(200, $send_data, self::$msg);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function staff_view()
    {
        $forms = check_form();
        try {
            $person                                 = new staffModel();
            $person->person_id                      = get_form($forms, 'person_id', ['notnull']);
            $person_data                            = $person->staff_one();

            if ($person_data != []) {
                $person_data['person_name']         = $person_data['person_name'];
                $person_data['person_lastname']     = $person_data['person_lastname'];
                $person_data['person_picture']      = IMAGES . $person_data['person_picture'];
                $person_data['keywords']            = generate_keywords([$person_data['person_name'], $person_data['person_lastname'], $person_data['person_document'], $person_data['person_cellphone']]);
                $person_data['spinner']             = false;
            }
            $_send['staff_form'] = $person_data;

            json_response(200, $_send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function staff_new()
    {
        try {
            $data_person['person_id']           = '';
            $data_person['person_picture']      = IMAGES . person_picture(3);
            $data_person['person_name']         = '';
            $data_person['person_lastname']     = '';
            $data_person['person_document']     = 'd' . rand(1, 999999);
            $data_person['person_birthday']     = '1980-01-01';
            $data_person['person_gender']       = 3;
            $data_person['person_cellphone']    = '';
            $data_person['person_address']      = '';
            $data_person['person_city']         = '';
            $data_person['person_postalcode']   = '';
            $data_person['person_email']        = '';
            $data_person['person_created']      = date_js();
            $data_person['person_employee']     = 1;

            $_send['staff_form']                = $data_person;
            json_response(200, $_send, 'Datos por defecto');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function staff_add()
    {
        $forms = check_form();
        try {
            $person                             = new staffModel();
            # Cargamos todos los datos enviados por el usuario y preparamos todo para agregarlo a la base de datos
            $person->person_name                = get_form($forms, 'person_name', ['strtolower', 'notnull']);
            $person->person_lastname            = get_form($forms, 'person_lastname', ['strtolower', 'notascii']);
            $person->person_document            = get_form($forms, 'person_document', ['notnull']);
            $person->person_picture             = get_form($forms, 'person_picture', ['image']);
            $person->person_birthday            = get_form($forms, 'person_birthday', ['notnull'], date_js());
            $person->person_gender              = get_form($forms, 'person_gender', ['notnull'], '3');
            $person->person_cellphone           = get_form($forms, 'person_cellphone', ['cellphone']);
            $person->person_address             = get_form($forms, 'person_address', ['strtolower', 'notascii']);
            $person->person_city                = get_form($forms, 'person_city', ['strtolower', 'notascii']);
            $person->person_postalcode          = get_form($forms, 'person_postalcode', []);
            $person->person_email               = get_form($forms, 'person_email', []);
            $person->person_observation         = get_form($forms, 'person_observation', []);
            $person->person_employee            = get_form($forms, 'person_employee', ['notnull'], 1);
            $person->person_pass                = hash_pass($person->person_document);
            $person->person_zone                = 2;
            $person->person_condition           = 1;
            $person->person_vip                 = 0;
            $person->person_prefer_address      = 0;
            $person->person_prefer_store        = self::$user->store_id;
            $person->person_created             = now();
            # Con los datos pre cargados nos fijamos si el numero esta asociado.-
            $data_person                        = $person->check_telephone();
            if ($data_person != []) {
                json_response(400, null, 'Existe individuo con mismo celular. Contacte a soporte');
            }
            $data_person                        = $person->check_document();
            if ($data_person != []) {
                json_response(400, null, 'Existe individuo con mismo dni. Contacte a soporte');
            }
            $person->staff_add();

            json_response(200, null, 'Registro creado');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function staff_update()
    {
        $forms = check_form();
        try {
            $person                         = new staffModel();
            $person->person_id              = get_form($forms, 'person_id', ['notnull']);
            # Cargamos todos los datos enviados por el usuario y preparamos todo para agregarlo a la base de datos
            $person->person_document        = get_form($forms, 'person_document', ['notnull']);
            $person->person_picture         = get_form($forms, 'person_picture', ['image']);
            $person->person_name            = get_form($forms, 'person_name', ['strtolower', 'notnull', 'notascii']);
            $person->person_lastname        = get_form($forms, 'person_lastname', ['strtolower', 'notascii']);
            $person->person_birthday        = get_form($forms, 'person_birthday', ['notnull'], date_js());
            $person->person_gender          = get_form($forms, 'person_gender', ['notnull'], '3');
            $person->person_address         = get_form($forms, 'person_address', ['strtolower', 'notascii']);
            $person->person_city            = get_form($forms, 'person_city', ['strtolower', 'notascii']);
            $person->person_postalcode      = get_form($forms, 'person_postalcode', []);
            $person->person_email           = get_form($forms, 'person_email', []);
            $person->person_employee        = get_form($forms, 'person_employee', ['notnull'], 1);
            $person->person_observation     = get_form($forms, 'person_observation', []);
            $person->person_lastedit        = now();

            $person->staff_update();

            json_response(200, null, 'Registro actualizado');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function staff_delete()
    {
        $forms = check_form();
        try {
            $person_id  = get_form($forms, 'person_id', ['notnull']);

            person_condition($person_id, -1);
            json_response(200, null, 'Registro eliminado. Para restaurar debera contactar a soporte');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage() . ' pacienteController');
        }
    }
    function staff_status()
    {
        $forms = check_form();
        try {
            $person_id          = get_form($forms, 'person_id', ['notnull']);
            $person_condition   = get_form($forms, 'person_condition', ['notnull']);
            $condition          = ($person_condition != 0) ? 0 : 1;

            person_condition($person_id, $condition);
            $this->staff_view();
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage() . ' staff_status');
        }
    }
    /** Consulta el personal que ha iniciado jornada */
    function staff_present()
    {
        $detect             = new Mobile_Detect;
        $persons            = new staffModel();
        $persons->store_id  = self::$user->store_id;
        $staff_present      = $persons->staff_present();

        if ($staff_present != []) {
            foreach ($staff_present as $ix_p => $vl_p) {
                $staff_present[$ix_p]['person_name']         = ucwords($vl_p['person_name']);
                $staff_present[$ix_p]['person_lastname']     = ucwords($vl_p['person_lastname']);
                $staff_present[$ix_p]['person_picture']      = IMAGES . $vl_p['person_picture'];
                $staff_present[$ix_p]['attendance_entry_agent']  = $detect->isMobile($vl_p['attendance_entry_agent']) ? ($detect->isTablet($vl_p['attendance_entry_agent']) ? 'tablet' : 'phone') : 'computer';
            }
        }
        $send['staff_present'] = $staff_present;

        json_response(200, $send);
    }
    /* ================== ACCESOS - Requiere tabla persons_access ================== */
    function access_save()
    {
        $forms = check_form();

        $person                         = new staffModel();
        $person->person_access_bound    = get_form($forms, 'person_id', ['notnull']);
        $person->person_access_store    = self::$user->store_id;
        # Limpiamos perfiles
        $person->access_delete();
        # Generamos los perfiles
        $profiles_enabled   = get_form($forms, 'profiles_enabled', ['notnull']);

        foreach ($profiles_enabled as $profile) {
            $person->person_access_profile = $profile;
            $person->access_add();
        }

        json_response(200, null, 'Perfiles actualizado');
    }
}
