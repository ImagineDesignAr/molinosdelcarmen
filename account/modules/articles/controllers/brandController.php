<?php
class brandController extends Controller
{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
        #check_methods(CONTROLLER, METHOD, self::$user->profile);
    }

    function add_brand()
    {
        try {
            $brand_name                     = get_form($_POST, 'brand_name', ['ucwords', 'notnull']);

            $brands                         = new brandsModel();
            $brands->brand_class            = 'physical';
            $brands->brand_subcategory      = 0;
            $brands->brand_name             = $brand_name;
            $brands->brand_abbreviation     = replace_chars($brand_name);
            $brands->brand_condition        = 1;

            if (!$brands->exists()) {
                $category_id                    = $brands->add();
                json_response(200, $category_id, 'Marca creada');
            } else {
                json_response(400, null, 'La marca ya existe');
            }
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function brand_list()
    {
        try {
            # Obtener todas las marcas disponibles
            $brands                 = new brandsModel();
            $send['brand_selected'] = $_SESSION['defaults']['brand_physical'];
            $send['brand_listed']   = $brands->all();

            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
