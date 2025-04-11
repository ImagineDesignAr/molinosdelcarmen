<?php
function token_pr($store_id, $printer_id)
# Token para usar impresora desde aplicativo IDPrinter
{
    $token['store_id']              = $store_id;
    $token['printer_id']            = $printer_id;
    $token                          = to_json($token);
    $token                          = iD_encrypt($token);
    return $token;
}
function printer_store($store_id)
{
    $store                  = new spoolingModel();
    $store->spooler_store   = $store_id;
    $printer_data           = $store->printer_default();

    return $printer_data;
}
function printer_data($printer_id)
{
    $spooling                  = new spoolingModel();
    $spooling->spooler_printer = $printer_id;
    return $spooling->printer_one();
}
# Añadir orden a cola de impresion
function add_spooler($order_id, $printer_id, $type, $store_id)
{
    $spooler                    = new spoolingModel();
    $spooler->spooler_printer   = $printer_id;
    $spooler->spooler_store     = $store_id;
    $spooler->spooler_order     = $order_id;
    $spooler->spooler_type      = $type;
    $spooler->spooler_condition = 0;
    $spooler->spooler_add();
}
# Funcion para establecer estado IMPRESO
function spooler_process($spooler_id)
{
    $spooler = new spoolingModel();
    $spooler->spooler_process($spooler_id);
}

function add_row(array &$ticket, $align = 'left', $size = 10, $bold = false, $text = '', $line = false)
{
    $ticket['ticket_rows'][] = ['align' => $align, 'size' => $size, 'bold' => $bold, 'text' => $text, 'line' => $line];
}
