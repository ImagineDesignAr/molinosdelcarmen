<?php
# Controlador de sucursales
class storeController extends Controller

{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login');
    }
    function store_current()
    {
        try {
            $_send['store_one'] = private_store(self::$user->store_id);

            json_response(200, $_send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function store_list()
    {
        try {
            $_send['store_listed'] = stores_type('store');
            json_response(200, $_send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function store_add()
    {
        $form                              = check_form('form');

        try {
            $store                          = new storesModel();
            $store_type                     = get_form($form, 'store_type', ['notnull']);

            $store->store_picture           = get_form($form, 'store_picture', ['image']);
            $store->store_type              = $store_type;
            $store->store_name              = get_form($form, 'store_name', ['notnull']);
            $store->store_shortname         = get_form($form, 'store_shortname', []);
            $store->store_description       = get_form($form, 'store_description', []);
            $store->store_address           = get_form($form, 'store_address', []);
            $store->store_phonenumber       = get_form($form, 'store_phonenumber', []);
            $store->store_instagram         = get_form($form, 'store_instagram', []);
            $store->store_facebook          = get_form($form, 'store_facebook', []);
            $store->store_email             = get_form($form, 'store_email', []);
            $store->store_web               = get_form($form, 'store_web', []);

            $store_id = $store->store_add();

            # Agregar registro en tabla horarios
            $store->hour_add($store_id);
            # Agregar registro en tabla fiscal
            $store->estate_add($store_id);
            # Agregar registro en tabla zonas
            self::zones_add($store_id);
            # Agregar perfil de administrador
            self::access_save($store_id);

            #($store_type == 'store') ? restaurant_enabled && set_store($store_id) : false;

            logger("Se creo nueva sucursal");
            json_response(200, null, 'Sucursal Agregada');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function store_update()
    {
        $form                              = check_form('form');

        try {
            # Actulizamos campos de sucursal
            $store                          = new storesModel();

            $store->store_id                = get_form($form, 'store_id', ['notnull']);
            $store->store_picture           = get_form($form, 'store_picture', ['image']);
            $store->store_name              = get_form($form, 'store_name', ['notnull']);
            $store->store_shortname         = get_form($form, 'store_shortname', []);
            $store->store_description       = get_form($form, 'store_description', []);
            $store->store_address           = get_form($form, 'store_address', []);
            $store->store_phonenumber       = get_form($form, 'store_phonenumber', []);
            $store->store_instagram         = get_form($form, 'store_instagram', []);
            $store->store_facebook          = get_form($form, 'store_facebook', []);
            $store->store_email             = get_form($form, 'store_email', []);
            $store->store_web               = get_form($form, 'store_web', []);

            $store->store_update();
            json_response(200, null, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function store_status()
    {
        $form                               = check_form('form');
        $store_id                           = get_form($form, 'store_id', ['notnull']);
        $store_condition                    = get_form($form, 'store_condition', ['notnull']);
        $store_condition                    = ($store_condition != 0) ? 0 : 1;

        try {
            $store                          = new storesModel();
            $store->store_id                = $store_id;
            $store->store_condition         = $store_condition;
            $store->store_status();

            $send_data['store_condition']   = $store_condition;

            json_response(200, $send_data, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function store_delete()
    {
        $form                               = check_form('form');
        $store_id                           = get_form($form, 'store_id', ['notnull']);
        $store_condition                    = -1;

        try {
            # Actulizamos campos de sucursal
            $store                          = new storesModel();
            $store->store_id                = $store_id;
            $store->store_condition         = $store_condition;
            $store->store_status();
            $this->store_list();
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function hour_list()
    {
        $form                           = check_form('form');
        $store_id                       = get_form($form, 'store_id', ['notnull']);

        try {
            # Actulizamos campos de sucursal
            $store                      = new storesModel();
            $send_data['store_time']    = $store->hour_list($store_id);

            json_response(200, $send_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function hour_update()
    {
        $form                               = check_form('form');

        try {
            # Actulizamos campos de sucursal
            $store                          = new storesModel();

            $store->store_hour_bound        = get_form($form, 'store_hour_bound', ['notnull']);
            $store->store_hour_status       = get_form($form, 'store_hour_status', []);
            $store->store_hour_open_am      = get_form($form, 'store_hour_open_am', []);
            $store->store_hour_close_am     = get_form($form, 'store_hour_close_am', []);
            $store->store_hour_open_pm      = get_form($form, 'store_hour_open_pm', []);
            $store->store_hour_close_pm     = get_form($form, 'store_hour_close_pm', []);

            $store->hour_update();

            json_response(200, null, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function zone_list()
    {
        $form                           = check_form('form');
        $store_id                       = get_form($form, 'store_id', ['notnull'], self::$user->store_id);

        try {
            $store                      = new storesModel();
            $send_data['store_zone']    = $store->zone_list($store_id);

            json_response(200, $send_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function zone_update()
    {
        $form                           = check_form('form');

        try {
            $store                      = new storesModel();
            foreach ($form as $ix_zone => $vl_zone) {
                $store->store_zone_id       = get_form($vl_zone, 'store_zone_id', ['notnull']);
                $store->store_zone_name     = get_form($vl_zone, 'store_zone_name', []);
                $store->store_zone_cost     = get_form($vl_zone, 'store_zone_cost', ['positive']);
                $store->store_zone_gmaps    = get_form($vl_zone, 'store_zone_gmaps', []);
                $store->store_zone_orderby  = get_form($vl_zone, 'store_zone_orderby', ['positive']);
                $store->zone_update();
            }

            json_response(200, null, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function estate_list()
    {
        $form                           = check_form('form');
        $store_id                       = get_form($form, 'store_id', ['notnull']);

        try {
            # Actulizamos campos de sucursal
            $store                      = new storesModel();
            $send_data['store_estate']  = $store->estate_list($store_id);

            json_response(200, $send_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function estate_update()
    {
        $form                                   = check_form('form');

        try {
            # Actulizamos campos de sucursal
            $store                              = new storesModel();

            $store->store_estate_bound          = get_form($form, 'store_estate_bound', ['notnull']);
            $store->store_estate_cuit           = get_form($form, 'store_estate_cuit', []);
            $store->store_estate_business       = get_form($form, 'store_estate_business', []);
            $store->store_estate_income         = get_form($form, 'store_estate_income', []);
            $store->store_estate_start          = get_form($form, 'store_estate_start', []);
            $store->store_estate_vat            = get_form($form, 'store_estate_vat', []);
            $store->store_estate_crt            = get_form($form, 'store_estate_crt', []);
            $store->store_estate_key            = get_form($form, 'store_estate_key', []);
            $store->store_estate_folder         = get_form($form, 'store_estate_folder', []);
            $store->store_estate_expiration     = get_form($form, 'store_estate_expiration', []);
            $store->store_estate_production     = get_form($form, 'store_estate_production', []);
            $store->store_estate_point          = get_form($form, 'store_estate_point', []);
            $store->store_estate_invoice        = get_form($form, 'store_estate_invoice', []);
            $store->store_estate_condition      = get_form($form, 'store_estate_condition', []);

            $store->estate_update();

            json_response(200, null, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    private function zones_add($store_id)
    {
        try {
            # Actulizamos campos de sucursal
            $c = 12;
            $store                      = new storesModel();
            for ($i = 1; $i <= $c; $i++) {
                $store->store_zone_bound    = $store_id;
                $store->store_zone_name     = 'Zona ' . $i;
                $store->store_zone_cost     = 0;
                $store->store_zone_gmaps    = "";
                $store->store_zone_orderby  = $i;

                $store->zone_add();
            }
            logger("Se creo listado de zonas");
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    /* ================== ACCESOS - Requiere tabla persons_access ================== */
    private function access_save($store_id)
    {
        $person                         = new staffModel();
        $person->person_access_bound    = 1; # Cuenta de jorge
        $person->person_access_store    = $store_id;
        $person->person_access_profile  = 1; # Perfil administrador
        $person->access_add();
    }
}
