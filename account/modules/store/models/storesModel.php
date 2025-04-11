<?php

class storesModel extends Model
{
    # stores
    public $store_id;
    public $store_picture;
    public $store_type;
    public $store_name;
    public $store_shortname;
    public $store_description;
    public $store_address;
    public $store_phonenumber;
    public $store_facebook;
    public $store_instagram;
    public $store_email;
    public $store_web;
    public $store_lat;
    public $store_lng;
    public $store_color;
    public $store_radius;
    public $store_condition;
    # stores_api
    public $stores_api;
    public $store_api_bound; # store_id asociada
    public $store_api_platform;
    public $store_api_key;
    public $store_api_secret;
    public $store_api_token;
    public $store_api_condition;
    # stores_hour
    public $store_hour_bound; # store_id asociada
    public $store_hour_status;
    public $store_hour_open_am;
    public $store_hour_close_am;
    public $store_hour_open_pm;
    public $store_hour_close_pm;
    # stores_discount
    public $store_discount_bound; # store_id asociada
    public $store_discount_type;
    public $store_discount_paymethod;
    public $store_discount_condition;
    # stores_estate
    public $store_estate_id;
    public $store_estate_bound;
    public $store_estate_cuit;
    public $store_estate_business;
    public $store_estate_income;
    public $store_estate_start;
    public $store_estate_vat;
    public $store_estate_crt;
    public $store_estate_key;
    public $store_estate_expiration;
    public $store_estate_folder;
    public $store_estate_production;
    public $store_estate_point;
    public $store_estate_invoice;
    public $store_estate_condition;
    # stores_estate
    public $store_zone_id;
    public $store_zone_bound;
    public $store_zone_name;
    public $store_zone_cost;
    public $store_zone_gmaps;
    public $store_zone_orderby;
    # stores_printer
    public $store_printer_id;
    public $store_printer_bound;
    public $store_printer_name;
    public $store_printer_initial_height;
    public $store_printer_left_margin;
    public $store_printer_leading;
    public $store_printer_width;
    public $store_printer_font;
    public $store_printer_condition;

    /* ================== ABM - Tabla stores ================== */
    /** Trae todas las sucursales segun store_type */
    public function stores_type($store_type = 'all')
    {
        $params = [];
        $sql = "SELECT store_id,store_picture,store_name,store_shortname,store_address,
        store_description,
        store_address,
        store_phonenumber,
        store_facebook,
        store_instagram,
        store_email,
        store_web,
        store_condition,
        store_lat,
        store_lng,
        store_icon,
        store_color,
        store_radius,
        IFNULL(store_cash,0) AS store_cash,
        IFNULL(store_hour_status,0) AS store_hour_status
        FROM `stores` 
        LEFT JOIN stores_hour ON store_hour_bound=store_id
        WHERE store_condition != -1";

        if ($store_type != 'all') {
            $sql    = $sql . " AND store_type=:store_type";
            $params = ['store_type' => $store_type];
        }

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Trae los datos de la sucursal seleccionada */
    public function store_one()
    {
        $params['store_id'] = $this->store_id;
        $sql = "SELECT store_id,store_picture,store_name,store_shortname,store_address,
        store_description,
        store_address,
        store_phonenumber,
        store_facebook,
        store_instagram,
        store_email,
        store_web,
        store_condition,
        IFNULL(store_cash,0) AS store_cash,
        IFNULL(store_hour_status,0) AS store_hour_status
        FROM `stores` 
        LEFT JOIN stores_hour       ON store_hour_bound=store_id
        WHERE store_id=:store_id OR store_name=:store_id LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Agrega una sucursal con los datos basicos */
    public function store_add()
    {
        $params['store_picture']        = $this->store_picture;
        $params['store_type']           = $this->store_type;
        $params['store_name']           = $this->store_name;
        $params['store_shortname']      = $this->store_shortname;
        $params['store_description']    = $this->store_description;
        $params['store_address']        = $this->store_address;
        $params['store_phonenumber']    = $this->store_phonenumber;
        $params['store_instagram']      = $this->store_instagram;
        $params['store_facebook']       = $this->store_facebook;
        $params['store_email']          = $this->store_email;
        $params['store_web']            = $this->store_web;
        $sql = "INSERT INTO `stores` SET ";
        $sql = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza todos los datos de la sucursal */
    public function store_update()
    {
        $params['store_id']             = $this->store_id;
        $params['store_picture']        = $this->store_picture;
        $params['store_name']           = $this->store_name;
        $params['store_shortname']      = $this->store_shortname;
        $params['store_description']    = $this->store_description;
        $params['store_address']        = $this->store_address;
        $params['store_phonenumber']    = $this->store_phonenumber;
        $params['store_instagram']      = $this->store_instagram;
        $params['store_facebook']       = $this->store_facebook;
        $params['store_email']          = $this->store_email;
        $params['store_web']            = $this->store_web;
        $params['store_lat']            = $this->store_lat;
        $params['store_lng']            = $this->store_lng;
        $params['store_color']          = $this->store_color;
        $params['store_radius']         = $this->store_radius;

        $sql = "UPDATE `stores` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_id=:store_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza el estado de la sucursal */
    public function store_status()
    {
        $params['store_id']         = $this->store_id;
        $params['store_condition']  = $this->store_condition;
        $sql = "UPDATE `stores` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_id=:store_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* ================== HORARIOS - Requiere tabla stores_hour ================== */
    /**  Agrega campos predeterminado de horarios */
    public function hour_add($store_id)
    {
        $params['store_hour_bound']     = $store_id;
        $params['store_hour_status']    = 1;
        $params['store_hour_open_am']   = '00:00';
        $params['store_hour_close_am']  = '00:00';
        $params['store_hour_open_pm']   = '00:00';
        $params['store_hour_close_pm']  = '00:00';

        $sql = "INSERT INTO `stores_hour` SET ";
        $sql = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Trae horarios */
    public function hour_list($store_id)
    {
        $params = ['store_hour_bound' => $store_id];
        $sql = "SELECT 
        store_name,
        store_hour_bound,
        store_hour_status,
        store_hour_open_am,
        store_hour_close_am,
        store_hour_open_pm,
        store_hour_close_pm
        FROM `stores_hour` 
        INNER JOIN `stores` ON store_id=store_hour_bound
        WHERE store_hour_bound=:store_hour_bound";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza horarios */
    public function hour_update()
    {
        $params['store_hour_bound']     = $this->store_hour_bound;
        $params['store_hour_status']    = $this->store_hour_status;
        $params['store_hour_open_am']   = $this->store_hour_open_am;
        $params['store_hour_close_am']  = $this->store_hour_close_am;
        $params['store_hour_open_pm']   = $this->store_hour_open_pm;
        $params['store_hour_close_pm']  = $this->store_hour_close_pm;

        $sql = "UPDATE `stores_hour` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_hour_bound=:store_hour_bound";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* ================== ZONAS DELIVERY - Requiere tabla stores_zone ================== */
    /**  Trae todas las zonas de la sucursal */
    public function zone_list($store_id)
    {
        $params = ['store_zone_bound' => $store_id];
        $sql = "SELECT 
        store_name,
        store_zone_id,
        store_zone_name,
        store_zone_cost,
        store_zone_gmaps,
        store_zone_orderby
        FROM `stores_zone` 
        INNER JOIN `stores` ON store_id=store_zone_bound 
        WHERE store_zone_bound=:store_zone_bound 
        ORDER BY store_zone_orderby asc";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function zone_one($store_zone_id)
    {
        $params['store_zone_id'] = $store_zone_id;
        $sql = "SELECT 
        store_name,
        store_zone_id,
        store_zone_bound,
        store_zone_name,
        store_zone_cost,
        store_zone_gmaps,
        store_zone_orderby
        FROM `stores_zone` 
        INNER JOIN `stores` ON store_id=store_zone_bound
        WHERE store_zone_id=:store_zone_id";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Agrega zonas a las sucursales */
    public function zone_add()
    {
        $params['store_zone_bound']     = $this->store_zone_bound;
        $params['store_zone_name']      = $this->store_zone_name;
        $params['store_zone_cost']      = $this->store_zone_cost;
        $params['store_zone_gmaps']     = $this->store_zone_gmaps;
        $params['store_zone_orderby']   = $this->store_zone_orderby;

        $sql = "INSERT INTO `stores_zone` SET ";
        $sql = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza zonas a las sucursales */
    public function zone_update()
    {
        $params['store_zone_id']        = $this->store_zone_id;
        $params['store_zone_name']      = $this->store_zone_name;
        $params['store_zone_cost']      = $this->store_zone_cost;
        $params['store_zone_gmaps']     = $this->store_zone_gmaps;
        $params['store_zone_orderby']   = $this->store_zone_orderby;

        $sql = "UPDATE `stores_zone` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_zone_id=:store_zone_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* ================== DATOS TRIBUTARIOS - Requiere tabla stores_estate ================== */
    public function estate_add($store_id)
    {
        $params['store_estate_bound']       = $store_id;
        $params['store_estate_cuit']        = NULL;
        $params['store_estate_business']    = NULL;
        $params['store_estate_income']      = NULL;
        $params['store_estate_start']       = NULL;
        $params['store_estate_vat']         = NULL;
        $params['store_estate_crt']         = NULL;
        $params['store_estate_key']         = NULL;
        $params['store_estate_folder']      = NULL;
        $params['store_estate_expiration']  = NULL;
        $params['store_estate_production']  = NULL;
        $params['store_estate_point']       = NULL;
        $params['store_estate_invoice']     = NULL;
        $params['store_estate_condition']   = 1;

        $sql = "INSERT INTO `stores_estate` SET ";
        $sql = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Trae los datos tributarios de la sucursal */
    public function estate_list($store_id)
    {
        $params = ['store_estate_bound' => $store_id];
        $sql = "SELECT 
        store_name,
        store_estate_bound,
        store_estate_cuit,
        store_estate_business,
        store_estate_income,
        store_estate_start,
        store_estate_vat,
        store_estate_crt,
        store_estate_key,
        store_estate_folder,
        store_estate_expiration,
        store_estate_production,
        store_estate_point,
        store_estate_invoice,
        store_estate_condition
        FROM `stores_estate`
        INNER JOIN `stores` ON store_estate_bound=store_id
        WHERE store_estate_bound=:store_estate_bound";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza datos */
    public function estate_update()
    {
        $params['store_estate_bound']       = $this->store_estate_bound;
        $params['store_estate_cuit']        = $this->store_estate_cuit;
        $params['store_estate_business']    = $this->store_estate_business;
        $params['store_estate_income']      = $this->store_estate_income;
        $params['store_estate_start']       = $this->store_estate_start;
        $params['store_estate_vat']         = $this->store_estate_vat;
        $params['store_estate_crt']         = $this->store_estate_crt;
        $params['store_estate_key']         = $this->store_estate_key;
        $params['store_estate_folder']      = $this->store_estate_folder;
        $params['store_estate_expiration']  = $this->store_estate_expiration;
        $params['store_estate_production']  = $this->store_estate_production;
        $params['store_estate_point']       = $this->store_estate_point;
        $params['store_estate_invoice']     = $this->store_estate_invoice;

        $sql = "UPDATE `stores_estate` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_estate_bound=:store_estate_bound";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* ================== IMPRESORAS COMANDERAS - Requiere tabla stores_printer ================== */
    public function default_store()
    {
        $params['store_printer_bound'] = $this->store_printer_bound;
        $sql = "SELECT * FROM `stores_printer` WHERE store_printer_bound=:store_printer_bound AND store_printer_condition = 1 LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* ================== FACTURACION AFIP - Requiere tabla stores_estate ================== */
    public function estate_data($store_id)
    {
        $params['store_id'] = $store_id;
        $sql = "SELECT 
        store_estate_cuit,
        store_estate_business,
        store_estate_income,
        store_estate_start,
        store_estate_vat,
        store_estate_crt,
        store_estate_key,
        store_estate_passphrase,
        store_estate_folder,
        store_estate_expiration,
        store_estate_production,
        store_estate_point,
        store_estate_invoice,
        store_estate_condition
        FROM `stores_estate` 
        WHERE store_estate_bound=:store_id LIMIT 1";

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
