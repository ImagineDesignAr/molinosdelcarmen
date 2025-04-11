<?php

class categoriesModel extends Model
{
    public $category_id;
    public $category_class;
    public $category_subcategory;
    public $category_name;
    public $category_abbreviation;
    public $category_condition;

    public function category_add()
    {
        $params['category_class']           = $this->category_class;
        $params['category_subcategory']     = $this->category_subcategory;
        $params['category_name']            = $this->category_name;
        $params['category_abbreviation']    = $this->category_abbreviation;
        $params['category_condition']       = $this->category_condition;
        $sql = "INSERT INTO `categories` SET ";
        $sql    = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function category_delete()
    {
        $params['category_id'] = $this->category_id;
        $sql = "DELETE FROM `categories` WHERE category_id=:category_id LIMIT 1";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function category_all()
    {
        $sql = "SELECT * FROM `categories` WHERE category_condition!=0";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    # Devuelve todas las categorias asociadas a articulos con manejo de stock
    public function category_stock()
    {
        $sql = "SELECT category_id,category_name 
                FROM `categories` 
                INNER JOIN `articles_category` ON article_category_id = category_id
                INNER JOIN `articles` ON article_id = article_category_bound
                WHERE category_condition!=0 AND article_stock=1
                GROUP BY category_id,category_name";
        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    # Verifica si existe una categoria con el mismo nombre
    public function category_exists()
    {
        $params['category_name'] = $this->category_name;
        $sql = "SELECT category_id FROM `categories` WHERE LOWER(category_name)=LOWER(:category_name)";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
