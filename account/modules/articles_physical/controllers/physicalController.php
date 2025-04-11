<?php
# Update 14/12/2024
class physicalController extends Controller
{
    public static $user;
    function __construct()
    {
        #check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
        #check_methods(CONTROLLER, METHOD, self::$user->profile);
    }
    function physicals_list() # v3
    {
        try {
            $physicals      = new articles_physicalModel();
            $physical_data  = $physicals->physical_list();
            if ($physical_data != []) {
                foreach ($physical_data as $index => $row) {
                    $physical_data[$index]['article_available']     = boolean_return($row['article_available']);
                    $physical_data[$index]['physical_picture']      = IMAGES . $row['physical_picture'];
                    $physical_data[$index]['keywords']              = generate_keywords([$row['physical_title'], $row['physical_presentation'], $row['physical_barcode'], $row['physical_sku']]);
                    $physical_data[$index]['condition_color']       = $row['article_condition_color'];
                }
            }
            $send['physical_listed'] = $physical_data;

            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function physical_drafts()
    {
        try {
            $physicals      = new articles_physicalModel();
            $physical_data  = $physicals->drafts();

            if ($physical_data != []) {
                foreach ($physical_data as $index => $row) {
                    $physical_data[$index]['physical_picture']  = IMAGES . $row['physical_picture'];
                    $physical_data[$index]['category']          = to_array($row['category']);
                }
            }
            json_response(200, $physical_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Globals
    function physical_new() # v3
    {
        # Antes de crear un nuevo articulo verificamos que tengan categorias creadas
        (category_all() != []) ? null : json_response(401, null, 'Debe crear al menos una categoria');

        $physical_form['article_class']             = 'physical';
        $physical_form['article_group']             = generate_ref();
        $physical_form['physical_local']            = true;
        $physical_form['physical_web']              = true;
        $physical_form['physical_sunday']           = true;
        $physical_form['physical_monday']           = true;
        $physical_form['physical_tuesday']          = true;
        $physical_form['physical_wednesday']        = true;
        $physical_form['physical_thursday']         = true;
        $physical_form['physical_friday']           = true;
        $physical_form['physical_saturday']         = true;
        $physical_form['physical_midday']           = true;
        $physical_form['physical_night']            = true;
        $physical_form['physical_article']          = '';
        $physical_form['physical_picture']          = IMAGES . '_nodisponible.jpg';
        $physical_form['physical_title']            = '';
        $physical_form['physical_name']             = '';
        $physical_form['physical_barcode']          = '';
        $physical_form['physical_sku']              = generate_code(5, true);
        $physical_form['physical_presentation']     = '';
        $physical_form['physical_description']      = '';
        $physical_form['physical_attribute']        = '';
        $physical_form['physical_measure_id']          = 1;
        $physical_form['physical_cost']             = 0;
        $physical_form['physical_tax']              = 0;
        $physical_form['physical_utility']          = 0;
        $physical_form['physical_price']            = 0;
        $physical_form['physical_offer_price']      = 0;
        $physical_form['physical_automatic_price']  = 1;
        $physical_form['physical_condition']        = 1;
        $physical_form['physical_brand_id']         = ($_SESSION['defaults']['brand_physical'] != 'all') ? $_SESSION['defaults']['brand_physical'] : '';
        $physical_form['category']                  = ($_SESSION['defaults']['category_physical'] != 'all') ? [$_SESSION['defaults']['category_physical']] : [];

        $send['physical_form'] = $physical_form;

        try {
            json_response(200, $send, 'Articulo por defecto');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage() . ' deletedrafts');
        }
    }
    function physical_empty()
    {
        try {
            $physicals      = new articles_physicalModel();
            $physicals->drafts_empty();

            json_response(200, null, 'Borradores eliminados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function physical_add() # v3
    {
        try {
            $forms                  = check_form();

            $category   = get_form($forms, 'category', [], []);
            if (!is_array($category) || empty($category)) {
                json_response(400, null, 'Formulario categorias incorrecto');
            }

            $brand              = get_form($forms, 'physical_brand_id', ['notext', 'strtolower'], 'Sin Marca');
            $brands             = new brandsModel();
            $brands->brand_name = $brand;
            $brand_id           = $brands->add_ifnotexist('physical');

            $articles                           = new articlesModel();
            $article_stock                      = get_form($forms, 'article_stock', ['boolean']);
            $articles->article_class            = get_form($forms, 'article_class', ['notnull']);
            $articles->article_group            = get_form($forms, 'article_group', ['notnull'], generate_ref());
            $articles->article_lastedit         = now();
            $articles->article_condition        = 1;
            $articles->article_available        = get_form($forms, 'article_available', ['boolean']);
            $articles->article_stock            = $article_stock;
            $article_id                         = $articles->article_add();

            $physical                           = new articles_physicalModel();
            $physical->physical_article         = $article_id;
            $physical->physical_picture         = get_form($forms, 'physical_picture', ['image', 'notnull'], '_nodisponible.jpg');
            $physical->physical_title           = get_form($forms, 'physical_title', ['notnull']);
            $physical->physical_barcode         = get_form($forms, 'physical_barcode', ['']);
            $physical->physical_sku             = get_form($forms, 'physical_sku', ['']);
            $physical->physical_presentation    = get_form($forms, 'physical_presentation', []);
            $physical->physical_description     = get_form($forms, 'physical_description', []);
            $physical->physical_attribute       = get_form($forms, 'physical_attribute', ['']);
            $physical->physical_measure_id      = get_form($forms, 'physical_measure_id', ['notnull'], 1);
            $physical->physical_metadata        = get_form($forms, 'physical_metadata', ['strtolower']);
            #$physical->physical_automatic_price = get_form($forms, 'physical_automatic_price', ['positive'], '0');
            #$physical->physical_cost            = get_form($forms, 'physical_cost', ['notnull'], 0);
            #$physical->physical_tax             = get_form($forms, 'physical_tax', ['notnull'], 0);
            #$physical->physical_utility         = get_form($forms, 'physical_utility', ['notnull'], 0);
            #$physical->physical_price           = get_form($forms, 'physical_price', ['notnull'], 0);
            #$physical->physical_offer_price     = get_form($forms, 'physical_offer_price', ['notnull'], 0);
            $physical->physical_local           = get_form($forms, 'physical_local', ['boolean']);
            $physical->physical_web             = get_form($forms, 'physical_web', ['boolean']);
            #$physical->physical_sunday          = get_form($forms, 'physical_sunday', ['boolean']);
            #$physical->physical_monday          = get_form($forms, 'physical_monday', ['boolean']);
            #$physical->physical_tuesday         = get_form($forms, 'physical_tuesday', ['boolean']);
            #$physical->physical_wednesday       = get_form($forms, 'physical_wednesday', ['boolean']);
            #$physical->physical_thursday        = get_form($forms, 'physical_thursday', ['boolean']);
            #$physical->physical_friday          = get_form($forms, 'physical_friday', ['boolean']);
            #$physical->physical_saturday        = get_form($forms, 'physical_saturday', ['boolean']);
            $physical->physical_brand_id        = $brand_id;

            #$physical->physical_maxsale        = get_form($forms, 'physical_maxsale', ['notnull'], 0);
            #$physical->physical_minsale        = get_form($forms, 'physical_minsale', ['notnull'], 0);
            $physical->physical_condition       = get_form($forms, 'physical_condition', ['positive'], 1);
            $physical->physical_add();

            # Asocia el articulo a las categorias seleccionadas
            $this->sets_category($article_id, $category);
            # Asocia el articulo a todas las sucursales si tiene manejo de stock
            $article_stock == 1 ? $this->add_article_stock($article_id) : false;

            json_response(200, null, 'Articulo creado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function physical_update() # v3
    {
        try {
            $forms                  = check_form();

            $category   = get_form($forms, 'category', [], []);
            if (!is_array($category) || empty($category)) {
                json_response(400, null, 'Formulario categorias incorrecto');
            }

            $brand              = get_form($forms, 'physical_brand_id', ['notnull']);
            $brands             = new brandsModel();
            $brands->brand_name = $brand;
            $brand_id           = $brands->add_ifnotexist('physical');

            $articles                           = new articlesModel();
            $article_id                         = get_form($forms, 'physical_article', ['notnull']);
            $article_stock                      = get_form($forms, 'article_stock', ['boolean']);
            $articles->article_id               = $article_id;
            $articles->article_available        = get_form($forms, 'article_available', ['boolean']);
            $articles->article_stock            = $article_stock;
            $articles->article_update();

            $physical                           = new articles_physicalModel();
            $physical->physical_article         = $article_id;
            $physical->physical_id              = get_form($forms, 'physical_id', ['notnull']);
            $physical->physical_picture         = get_form($forms, 'physical_picture', ['image', 'notnull'], '_nodisponible.jpg');
            $physical->physical_title           = get_form($forms, 'physical_title', ['notnull']);
            $physical->physical_barcode         = get_form($forms, 'physical_barcode', ['']);
            $physical->physical_sku             = get_form($forms, 'physical_sku', ['']);
            $physical->physical_presentation    = get_form($forms, 'physical_presentation', []);
            $physical->physical_description     = get_form($forms, 'physical_description', []);
            $physical->physical_attribute       = get_form($forms, 'physical_attribute', ['']);
            $physical->physical_measure_id      = get_form($forms, 'physical_measure_id', ['notnull'], 1);
            $physical->physical_metadata        = get_form($forms, 'physical_metadata', ['strtolower']);
            #$physical->physical_automatic_price = get_form($forms, 'physical_automatic_price', ['positive'], '0');
            #$physical->physical_cost            = get_form($forms, 'physical_cost', ['notnull'], 0);
            #$physical->physical_tax             = get_form($forms, 'physical_tax', ['notnull'], 0);
            #$physical->physical_utility         = get_form($forms, 'physical_utility', ['notnull'], 0);
            #$physical->physical_price           = get_form($forms, 'physical_price', ['notnull'], 0);
            #$physical->physical_offer_price     = get_form($forms, 'physical_offer_price', ['notnull'], 0);
            $physical->physical_local           = get_form($forms, 'physical_local', ['boolean']);
            $physical->physical_web             = get_form($forms, 'physical_web', ['boolean']);
            #$physical->physical_sunday          = get_form($forms, 'physical_sunday', ['boolean']);
            #$physical->physical_monday          = get_form($forms, 'physical_monday', ['boolean']);
            #$physical->physical_tuesday         = get_form($forms, 'physical_tuesday', ['boolean']);
            #$physical->physical_wednesday       = get_form($forms, 'physical_wednesday', ['boolean']);
            #$physical->physical_thursday        = get_form($forms, 'physical_thursday', ['boolean']);
            #$physical->physical_friday          = get_form($forms, 'physical_friday', ['boolean']);
            #$physical->physical_saturday        = get_form($forms, 'physical_saturday', ['boolean']);
            $physical->physical_brand_id        = $brand_id;
            $physical->physical_condition       = get_form($forms, 'physical_condition', ['positive'], 1);

            #$physical->physical_maxsale        = get_form($forms, 'physical_maxsale', ['notnull'], 0);
            #$physical->physical_minsale        = get_form($forms, 'physical_minsale', ['notnull'], 0);
            $physical->physical_update();

            # Asocia el articulo a las categorias seleccionadas
            $this->sets_category($article_id, $category);
            # Asocia el articulo a todas las sucursales si tiene manejo de stock
            $article_stock == 1 ? $this->add_article_stock($article_id) : false;

            json_response(200, null, 'Articulo creado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function physical_view() # v3
    {
        $forms = check_form();
        try {
            $articles_physical                          = new articles_physicalModel();
            $articles_physical->physical_article        = get_form($forms, 'physical_article', ['notnull']);
            $physical_data                              = $articles_physical->physical_view();
            if ($physical_data != []) {
                $physical_data['physical_picture']      = IMAGES . $physical_data['physical_picture'];
                $physical_data['category']              = to_array($physical_data['category']);
                $physical_data['physical_brand_id']     = $physical_data['brand_name'];
                $physical_data['physical_local']        = boolean_return($physical_data['physical_local']);
                $physical_data['physical_web']          = boolean_return($physical_data['physical_web']);
                $physical_data['physical_sunday']       = boolean_return($physical_data['physical_sunday']);
                $physical_data['physical_monday']       = boolean_return($physical_data['physical_monday']);
                $physical_data['physical_tuesday']      = boolean_return($physical_data['physical_tuesday']);
                $physical_data['physical_wednesday']    = boolean_return($physical_data['physical_wednesday']);
                $physical_data['physical_thursday']     = boolean_return($physical_data['physical_thursday']);
                $physical_data['physical_friday']       = boolean_return($physical_data['physical_friday']);
                $physical_data['physical_saturday']     = boolean_return($physical_data['physical_saturday']);
                $physical_data['article_available']     = boolean_return($physical_data['article_available']);
                $physical_data['article_stock']         = boolean_return($physical_data['article_stock']);
            }
            $send['physical_form'] = $physical_data;

            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function physical_group() # v3
    {
        $forms = check_form();
        try {
            $articles_physical                          = new articles_physicalModel();
            $articles_physical->physical_article        = get_form($forms, 'physical_article', ['notnull']);
            $physical_data                              = $articles_physical->physical_view();
            if ($physical_data != []) {
                $physical_data['physical_article']      = '';
                $physical_data['physical_title']        = '';
                $physical_data['physical_sku']          = generate_code(5, true);;
                $physical_data['physical_barcode']      = '';
                $physical_data['physical_picture']      = IMAGES . '_nodisponible.jpg';
                $physical_data['category']              = to_array($physical_data['category']);
                $physical_data['physical_brand_id']     = $physical_data['brand_name'];
                $physical_data['physical_local']        = boolean_return($physical_data['physical_local']);
                $physical_data['physical_web']          = boolean_return($physical_data['physical_web']);
                $physical_data['physical_sunday']       = boolean_return($physical_data['physical_sunday']);
                $physical_data['physical_monday']       = boolean_return($physical_data['physical_monday']);
                $physical_data['physical_tuesday']      = boolean_return($physical_data['physical_tuesday']);
                $physical_data['physical_wednesday']    = boolean_return($physical_data['physical_wednesday']);
                $physical_data['physical_thursday']     = boolean_return($physical_data['physical_thursday']);
                $physical_data['physical_friday']       = boolean_return($physical_data['physical_friday']);
                $physical_data['physical_saturday']     = boolean_return($physical_data['physical_saturday']);
                $physical_data['article_available']     = boolean_return($physical_data['article_available']);
                $physical_data['article_stock']         = boolean_return($physical_data['article_stock']);
            }
            $send['physical_form'] = $physical_data;

            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function physical_delete() # v3
    {
        $forms = check_form();
        try {
            $articles                      = new articlesModel();
            $articles->article_id          = get_form($forms, 'physical_article', ['notnull']);
            $articles->article_delete();

            json_response(200, null, 'Articulo actualizado correctamente');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function article_available() # v3
    {
        $forms  = check_form();

        try {
            $article_id        = get_form($forms, 'physical_article', ['notnull']);
            $article_available = get_form($forms, 'article_available', ['notnull']);
            ($article_available != 0) ? $article_available = 0 : $article_available = 1;

            $article = new articlesModel;
            $article->set_available($article_id, $article_available);

            json_response(200, null, 'Cambio de disponibilidad en sucursales');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function status_store()
    {
        $forms  = check_form();

        try {
            $article_stock_id           = get_form($forms, 'article_stock_id', ['notnull']);
            $article_stock_condition    = get_form($forms, 'article_stock_condition', ['notnull']);
            $condition_color            = ($article_stock_condition != 0) ? 'text-dark' : 'text-success';
            $article_stock_condition    = ($article_stock_condition != 0) ? 0 : 1;

            $article_stock                          = new articles_stockModel();
            $article_stock->article_stock_id        = $article_stock_id;
            $article_stock->article_stock_condition = $article_stock_condition;
            $article_stock->set_condition();

            $article_return['article_stock_condition']  = $article_stock_condition;
            $article_return['condition_color']          = $condition_color;

            json_response(200, $article_return, 'Cambio de visibilidad en sucursal');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Agrega el articulo a todas las categorias seleccionado por el usuario
    protected function sets_category($article_id, $data)
    {
        $associated_category                            = new articles_categoryModel();
        $associated_category->article_category_bound    = $article_id;
        $associated_category->delete_all();

        foreach ($data as $categoria) {
            $associated_category->article_category_id   = $categoria;
            $associated_category->add();
        }
    }
    # Agrega control de stock del articulo en todas las sucursales
    protected function add_article_stock($article_id)
    {
        # Obtenemos todas las depositos disponibles
        $data_store    = stores_type('storage');
        $article_stock = new articles_stockModel();

        foreach ($data_store as $vl_store) {
            $article_stock->article_stock_article   = $article_id;
            $article_stock->article_stock_store     = $vl_store['store_id'];
            $article_data                           = $article_stock->get_stock();
            if ($article_data == []) { # Verificar si ya no esta el stock administrado
                $article_stock->article_stock_count       = 0;
                $article_stock->article_stock_min         = 0;
                $article_stock->article_stock_condition   = 1;
                $article_stock->articles_stock_add();
            }
        }
        # Obtenemos todas las sucursales disponibles
        $data_store    = stores_type('store');
        $article_stock = new articles_stockModel();

        foreach ($data_store as $vl_store) {
            $article_stock->article_stock_article   = $article_id;
            $article_stock->article_stock_store     = $vl_store['store_id'];
            $article_data                           = $article_stock->get_stock();
            if ($article_data == []) { # Verificar si ya no esta el stock administrado
                $article_stock->article_stock_count       = 0;
                $article_stock->article_stock_min         = 0;
                $article_stock->article_stock_condition   = 1;
                $article_stock->articles_stock_add();
            }
        }
    }
}
