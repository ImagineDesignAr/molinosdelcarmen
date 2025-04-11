<?php

/** Consulta datos una sucursal */
function store_one($store_id)
{
    $stores             = new storesModel();
    $stores->store_id   = $store_id;
    $stores_data        = $stores->store_one();

    return $stores_data;
}
/** Consulta datos todas las sucursales */
function stores_type($type = 'all')
{
    $stores         = new storesModel();
    $stores_data    = $stores->stores_type($type);
    if ($stores_data != []) {
        foreach ($stores_data as $ix_store => $vl_store) {
            $stores_data[$ix_store]['store_picture']        = IMAGES . $vl_store['store_picture'];
            $stores_data[$ix_store]['store_hour_status']    = boolean_return($vl_store['store_hour_status']);
            $stores_data[$ix_store]['keywords']             = generate_keywords([$vl_store['store_name']]);
            $stores_data[$ix_store]['token']                = iD_encrypt($vl_store['store_id']);
            $stores_data[$ix_store]['spinner']              = false;
        }
    }
    return $stores_data;
}
/** Armado de sucursal para enviar al front publico */
function private_store($store_id)
{
    $send_data   = [];
    $stores_data = store_one($store_id);

    if ($stores_data != []) {
        $send_data['store_id']          = $stores_data['store_id'];
        $send_data['store_picture']     = IMAGES . $stores_data['store_picture'];
        $send_data['store_name']        = ucwords($stores_data['store_name']);
        $send_data['store_address']     = $stores_data['store_address'];
        $send_data['store_phonenumber'] = $stores_data['store_phonenumber'];
        $send_data['store_condition']   = $stores_data['store_condition'];
        $send_data['store_hour_status'] = boolean_return($stores_data['store_hour_status']);
        $send_data['store_cash']        = $stores_data['store_cash'];
        $send_data['keywords']          = generate_keywords([$stores_data['store_name']]);
        $send_data['token']             = URL . 'attendances/i/' . iD_encrypt($stores_data['store_id']);
        $send_data['spinner']           = false;
    }
    return $send_data;
}
/** Armado de sucursal para enviar al front publico */
function public_store($store_id)
{
    $send_data   = [];
    $stores_data = store_one($store_id);

    if ($stores_data != []) {
        $send_data['store_id']          = $stores_data['store_id'];
        $send_data['store_picture']     = IMAGES . $stores_data['store_picture'];
        $send_data['store_name']        = ucwords($stores_data['store_name']);
        $send_data['store_address']     = $stores_data['store_address'];
        $send_data['store_phonenumber'] = $stores_data['store_phonenumber'];
        $send_data['store_condition']   = $stores_data['store_condition'];
        $send_data['store_hour_status'] = boolean_return($stores_data['store_hour_status']);
        $send_data['store_cash']        = $stores_data['store_cash'];
        $send_data['keywords']          = generate_keywords([$stores_data['store_name']]);
        $send_data['spinner']           = false;
    }
    return $send_data;
}
/** Armado de sucursales para enviar al front publico */
function public_stores($type = 'all')
{
    $stores_data = stores_type($type);

    return array_map(function ($store) {
        return [
            'store_id'          => $store['store_id'],
            'store_picture'     => IMAGES . $store['store_picture'],
            'store_name'        => ucwords($store['store_name']),
            'store_address'     => $store['store_address'],
            'store_phonenumber' => $store['store_phonenumber'],
            'store_condition'   => $store['store_condition'],
            'store_hour_status' => boolean_return($store['store_hour_status']),
            'store_cash'        => $store['store_cash'],
            'keywords'          => generate_keywords([$store['store_name']]),
            'spinner'           => false,
        ];
    }, $stores_data);
}
/** Consulta y formatea todas las zonas */
function public_zones($store_id)
{
    $zones                      = new storesModel();
    $zones_data                 = $zones->zone_list($store_id);
    # Armo un array con el identificador zona_id como indice
    $zone_array             = [];
    foreach ($zones_data as $zone) {
        $zone_array[$zone['store_zone_id']] = $zone;
    }
    return $zone_array;
}
/** Consulta costo segun zona */
function zone_cost($zone_id)
{
    $stores_zone    = new storesModel();
    $zone_cost      = $stores_zone->zone_one($zone_id);
    return $zone_cost != [] ? $zone_cost['store_zone_cost'] : null;
}
function zone_list($store_id)
{
    $store = new storesModel();
    return $store->zone_list($store_id);
}

function estate_data($store_id)
{
    $store      = new storesModel();
    $store_data = $store->estate_data($store_id);
    if (empty($store_data)) return false; // Comprobar que vienen datos
    if ($store_data['store_estate_condition'] == 0) return false; // Comprueba que esta habilitado para facturar

    $store_data['store_estate_cuit']        = (int) $store_data['store_estate_cuit'];
    $store_data['store_estate_production']  = (bool)$store_data['store_estate_production'];
    $store_data['store_estate_passphrase']  = '';
    $store_data['store_estate_crt']         = (string) $store_data['store_estate_crt'];
    $store_data['store_estate_key']         = (string) $store_data['store_estate_key'];
    $store_data['store_estate_folder']      = FOLDER_CERT . $store_data['store_estate_folder'] . DS;
    $store_data['store_estate_point']       = (int) $store_data['store_estate_point'];
    $store_data['store_estate_invoice']     = (int) $store_data['store_estate_invoice'];

    return $store_data;
}
