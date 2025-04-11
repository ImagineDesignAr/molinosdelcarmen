<?php
# controlador cola de impresion
class spoolingController extends Controller

{
    public static $h1 = 24;
    public static $h2 = 20;
    public static $h3 = 16;
    public static $h4 = 14;
    public static $h5 = 12;
    public static $h6 = 10;
    function __construct() {}

    function p()
    {
        $_send['titulo']    =  "Comanda #123";
        $_send['fecha']     =  "2025-02-16 12:45";
        $_send['total']     =  20.00;
        $_send['qr_url']    =  'https://imaginedesign.ar';
        $_send['items'][]   =  ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => 8.50];
        $_send['items'][]   =  ['cantidad' => 1, 'nombre' => 'Papas fritas', 'precio' => 3.00];

        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json;charset=utf-8');

        echo json_encode($_send, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK); // JSON_PRETTY_PRINT, JSON_FORCE_OBJECT
    }
    function printer_list()
    {
        $spoolingModel              = new spoolingModel();
        $_send['printer_listed']    = $spoolingModel->printer_list();

        json_response(200, $_send);
    }
    # Genera a partir de la cola de impresion los tickets que seran enviados como un json a la app iDPrinter
    function spooler($token = null)
    {
        ($token) ?? json_response(403);

        try {
            $printer_id = iD_decrypt($token);
            (empty($form)) ?? json_response(403);

            # Consultar si existen ordenes en la cola de impresion
            $spooler                    = new spoolingModel();
            $spooler->spooler_printer   = $printer_id;
            $store_pending              = $spooler->spooler_pending();
            # Si no hay trabajos pendientes devuelve un array vacio
            (empty($store_pending)) ?? json_response(200, []);

            $order = [];
            foreach ($store_pending as $ix_spooler => $vl_spooler) {
                $order_id               = $vl_spooler['spooler_order'];
                $store_id               = $vl_spooler['spooler_store'];
                $printer_id             = $vl_spooler['spooler_printer'];
                $spooler_id             = $vl_spooler['spooler_id'];
                switch ($vl_spooler['spooler_type']) {
                    default:
                        echo 'comando';
                        break;
                }
                //array_push($order, $ticket_temps);
            }

            json_response(200, $order);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function generate_token($printer_id = null)
    {
        ($printer_id) ?? json_response(403);
        $token = iD_encrypt($printer_id);
        debug($token);
    }
    function view_token($token)
    {
        $token          = iD_decrypt($token);
        $token          = to_array($token, false);
        //$token          = to_encode($token);

        debug($token);
    }
    function afip_test($token = null)
    {

        try {

            $order           = array(
                array(
                    'initial_height'    => 20,
                    'left_margin'       => 1,
                    'leading'           => 10,
                    'width'             => 175,
                    'font'              => 'arial',
                    'logo'              => true,
                    'url_qr'            => 'https://afip.gob.ar',
                    'order_id'          => 20,
                    'ticket_rows'       => array(
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "ROLLS FACTORY"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Cuit: 30-30021312-5"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "IB: 1231232"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Tucuman 415, Godoy Cruz, Mendoza"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "-------------------"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "FACTURA ELECTRONICA B - 006"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Nro: 0005-0000012312"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Fecha: 05/03/2023"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "-------------------"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "1 x $ 2000 Armala de 15 "
                        ),
                        array(
                            'size' => 8,
                            'bold' => false,
                            'text' => "- Bs As Roll"
                        ),
                        array(
                            'size' => 8,
                            'bold' => false,
                            'text' => "- Wala Roll"
                        ),
                        array(
                            'size' => 9,
                            'bold' => false,
                            'text' => "- Huacama"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "-------------------"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Subtotal: $ 2000"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Descuentos: 0%"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Total a Pagar: $ 2000"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "-------------------"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Fecha Vto: 05/04/2023"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "CAE: 123123123123"
                        ),
                    )
                ),
            );

            json_response(200, $order, 'OK');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function order_test($token = null)
    {
        try {
            $order           = array(
                array(
                    'initial_height'    => 10,
                    'left_margin'       => 1,
                    'leading'           => 20,
                    'width'             => 300,
                    'font'              => 'Lucia Console',
                    'logo'              => true,
                    'url_qr'            => '',
                    'order_id'          => 20,
                    'ticket_rows' => array(
                        array(
                            'size' => 22,
                            'bold' => true,
                            'text' => "21:34"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "DELIVERY"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Pedido Local"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "Datos Cliente"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Nombre: Jorge Baldracchi"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Direccion: Alvarez Thomas 2409, Parana, Entre Rios"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Telefono: 1167292842"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "Detalle Orden"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "1 x $ 2000"
                        ),
                        array(
                            'size' => 18,
                            'bold' => false,
                            'text' => "Armala de 15 "
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "- Bs As Roll"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "- Wala Roll"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "- Huacama"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "-----------"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Subtotal: $ 2000"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Descuentos: 0%"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Total a Pagar: $ 2000"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "Medio de Pago"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "Efectivo"
                        ),
                    )
                ),
            );

            json_response(200, $order, 'OK');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function size_test($token = null)
    {
        try {
            $order         = array(
                array(
                    'initial_height'    => 100,
                    'left_margin'       => 1,
                    'leading'           => 10,
                    'width'             => 175,
                    'font'              => 'Lucina Console',
                    'logo'              => true,
                    'url_qr'            => 'https://imaginedesign.ar',
                    'order_id'          => 20,
                    'ticket_rows' => array(
                        array(
                            'size' => 8,
                            'bold' => true,
                            'text' => "Size 8"
                        ),
                        array(
                            'size' => 10,
                            'bold' => true,
                            'text' => "Size 10"
                        ),
                        array(
                            'size' => 12,
                            'bold' => true,
                            'text' => "Size 12"
                        ),
                        array(
                            'size' => 14,
                            'bold' => true,
                            'text' => "Size 14"
                        ),
                        array(
                            'size' => 16,
                            'bold' => true,
                            'text' => "Size 16"
                        ),
                        array(
                            'size' => 18,
                            'bold' => true,
                            'text' => "Size 18"
                        ),
                        array(
                            'size' => 20,
                            'bold' => true,
                            'text' => "Size 20"
                        ),
                        array(
                            'size' => 22,
                            'bold' => true,
                            'text' => "Size 22"
                        ),
                        array(
                            'size' => 24,
                            'bold' => true,
                            'text' => "Size 24"
                        ),
                        array(
                            'size' => 8,
                            'bold' => false,
                            'text' => "Size 8"
                        ),
                        array(
                            'size' => 10,
                            'bold' => false,
                            'text' => "Size 10"
                        ),
                        array(
                            'size' => 12,
                            'bold' => false,
                            'text' => "Size 12"
                        ),
                        array(
                            'size' => 14,
                            'bold' => false,
                            'text' => "Size 14"
                        ),
                        array(
                            'size' => 16,
                            'bold' => false,
                            'text' => "Size 16"
                        ),
                        array(
                            'size' => 18,
                            'bold' => false,
                            'text' => "Size 18"
                        ),
                        array(
                            'size' => 20,
                            'bold' => false,
                            'text' => "Size 20"
                        ),
                        array(
                            'size' => 22,
                            'bold' => false,
                            'text' => "Size 22"
                        ),
                        array(
                            'size' => 24,
                            'bold' => false,
                            'text' => "Size 24"
                        ),
                    )
                )
            );
            json_response(200, $order);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
