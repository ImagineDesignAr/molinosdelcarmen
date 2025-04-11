<?php
function profiles($type = 'all')
{
    $profiles           = new staffModel();
    $profiles           = $profiles->profile_list($type);
    $option_profiles    = '';
    foreach ($profiles as $profile) {
        $option_profiles .= sprintf('<option value="%s">%s</option>', $profile['person_profile_id'], $profile['person_profile_text']);
    }
    return $option_profiles;
}
function store($type = 'all')
{
    $stores         = public_stores($type);
    $option_store   = '';
    foreach ($stores as $store) {
        $option_store .= sprintf('<option value="%s">%s</option>', $store['store_id'], $store['store_name']);
    }
    return $option_store;
}