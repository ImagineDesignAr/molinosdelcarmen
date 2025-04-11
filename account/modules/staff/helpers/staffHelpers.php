<?php
/** Consulta las personas que pertenecen a un perfil especifico en sucursal*/
function public_profiles($store_id, $profile)
{
    $profiles                           = new staffModel();
    $profiles->person_access_store      = $store_id;
    $profiles->person_profile_name      = $profile;
    $profiles_data                      = $profiles->profile_selected();
    return $profiles_data;
}
