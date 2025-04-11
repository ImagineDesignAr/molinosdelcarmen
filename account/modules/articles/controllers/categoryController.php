<?php
// Actualizacion a usar en v5
class categoryController extends Controller
{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
        #check_methods(CONTROLLER, METHOD, self::$user->profile);
    }
    function category_add($category_class)
    {
        try {
            $category_name                      = get_form($_POST, 'category_name', ['ucwords', 'notnull']);

            $categories                         = new categoriesModel();
            $categories->category_class         = $category_class;
            $categories->category_subcategory   = 0;
            $categories->category_name          = $category_name;
            $categories->category_abbreviation  = replace_chars($category_name);
            $categories->category_condition     = 1;

            if (!$categories->category_exists()) {
                $category_id                    = $categories->category_add();
                json_response(200, $category_id, 'Categoria creada');
            } else {
                json_response(400, null, 'La categoria ya existe');
            }
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function category_delete()
    {
        $category_id = get_form($_POST, 'category_id', ['notnull']);
        try {
            $associated_category                         = new articles_categoryModel();
            $associated_category->article_category_id    = $category_id;

            if ($associated_category->all() != []) {
                json_response(400, null, 'Hay articulos asociados a la categoria. Consulte a soporte');
            }

            $categories                      = new categoriesModel();
            $categories->category_id         = $category_id;
            $categories->category_delete();

            json_response(200, null, 'Categoria borrada con exito');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function category_list()
    {
        try {
            # Obtener todas las categorias disponibles
            $categories                 = new categoriesModel();
            $send['category_listed']    = $categories->category_all();
            $send['category_selected']  = $_SESSION['defaults']['category_physical'];

            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
