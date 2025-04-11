<?php

/** Trae todos los datos de una persona */
function person_one($person_id)
{
    $persons            = new personModel();
    $persons->person_id = $person_id;
    $persons_data       = $persons->person_one();

    return $persons_data;
}
/** Consulta y formatea persona */
function private_person($person_id)
{
    $person_data = person_one($person_id);
    $_send = [];
    if ($person_data != []) {
        $_send['person_id']           = $person_data['person_id'];
        $_send['person_name']         = ucwords($person_data['person_name']);
        $_send['person_lastname']     = ucwords($person_data['person_lastname']);
        $_send['person_document']     = $person_data['person_document'];
        $_send['person_pass']         = $person_data['person_pass'];
        $_send['person_picture']      = IMAGES . $person_data['person_picture'];
        $_send['person_condition']    = $person_data['person_condition'];
    }
    return $_send;
}
function public_person($person_id)
{
    $person_data            = person_one($person_id);
    $_send = [];
    if ($person_data != []) {
        $_send['person_id']           = $person_data['person_id'];
        $_send['person_name']         = ucwords($person_data['person_name']);
        $_send['person_lastname']     = ucwords($person_data['person_lastname']);
        $_send['person_document']     = $person_data['person_document'];
        $_send['person_picture']      = IMAGES . $person_data['person_picture'];
        $_send['person_condition']    = $person_data['person_condition'];
    }
    return $_send;
}
/** Devuelve imagen predeterminada segun genero */
function person_picture($person_gender)
{
    switch ($person_gender) {
        case 1:
            $person_picture = '_nene.png';
            break;
        case 2:
            $person_picture = '_nena.png';
            break;
        case 3:
            $person_picture = '_nobinario.png';
            break;
    }
    return $person_picture;
}
/** Elimina persona colocando en person_condition -1 */
function person_delete($person_id)
{
    person_condition($person_id, -1);
}
/** Cambia estado de la persona */
function person_condition($person_id, $person_condition)
{
    $person                        = new personModel();
    $person->person_id             = $person_id;
    $person->person_condition      = $person_condition;
    $person->person_lastedit       = now();

    $person->person_condition();
}
/** Cambia password de persona */
function person_pass($person_id, $pass)
{
    $person                     = new personModel();
    $person->person_id          = $person_id;
    $person->person_pass        = hash_pass($pass);
    $person->person_lastedit    = now();

    return $person->pass_update();
}
/** Cambia password de persona */
function check_telephone($person_cellphone)
{
    $person             = new personModel();
    $person->person_id  = $person_cellphone;
    return $person->person_one();
}
function lastlogin($person_id)
{
    $person             = new personModel();
    $person->person_id  = $person_id;
    return $person->lastlogin();
}
