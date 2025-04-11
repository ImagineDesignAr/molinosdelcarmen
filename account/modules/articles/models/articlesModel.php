<?php

class articlesModel extends Model
{
    public $article_id;
    public $article_class;
    public $article_group;
    public $article_lastedit;
    public $article_condition;
    public $article_available;
    public $article_stock;

    public $article_category;
    public $store_id;

    /* Agregar articulo */
    public function article_add()
    {
        $params['article_class']        = $this->article_class;
        $params['article_group']        = $this->article_group;
        $params['article_lastedit']     = now();
        $params['article_condition']    = $this->article_condition;
        $params['article_available']    = $this->article_available;
        $params['article_stock']        = $this->article_stock;

        $sql = "INSERT INTO `articles` SET ";
        $sql    = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Modificar articulo */
    public function article_update()
    {
        $params['article_id']           = $this->article_id;
        $params['article_lastedit']     = now();
        $params['article_available']    = $this->article_available;
        $params['article_stock']        = $this->article_stock;

        $sql = "UPDATE `articles` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE article_id=:article_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Devuelve todos los articulos que no esten eliminados */
    public function article_all()
    {
        $sql = "SELECT * FROM `articles` WHERE article_condition>=0 ORDER BY article_id asc";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Devuelve todos los articulos con condicion 1 */
    public function article_active()
    {
        $sql = "SELECT * FROM `articles` WHERE article_condition=1 ORDER BY article_id asc";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Devuelve todos los articulos clase "food" */
    public function article_food()
    {
        $sql = "SELECT * FROM `articles` WHERE article_condition=1 AND article_class='food' ORDER BY article_id asc";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Devuelve todos los articulos clase "supply" */
    public function article_supply()
    {
        $sql = "SELECT * FROM `articles` WHERE article_condition=1 AND article_class='supply' ORDER BY article_id asc";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Devuelve todos los articulos disponibles */
    public function article_available()
    {
        $sql = "SELECT * FROM `articles` WHERE article_available=1 ORDER BY article_id asc";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Devuelve todos los articulos que maneja stock */
    public function article_stock()
    {
        $sql = "SELECT * FROM `articles` WHERE article_stock=1 AND article_condition=1 ORDER BY article_id asc";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Elimina el articulo. Pone en condition -1 */
    public function article_delete()
    {
        $params['article_id']          = $this->article_id;
        $params['article_lastedit']    = now();
        $params['article_condition']   = -1;
        $params['article_available']   = -1;

        $sql = "UPDATE `articles` 
        SET 
        article_lastedit=:article_lastedit,
        article_condition=:article_condition,
        article_available=:article_available
        WHERE article_id=:article_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Setea la condicion enviando como parametro article_id y condition */
    public function set_condition($article_id, $condition)
    {
        $params['article_id']           = $article_id;
        $params['article_condition']    = $condition;
        $params['article_lastedit']     = now();

        $sql = "UPDATE `articles` SET ";
        $sql    = generate_bind($sql, $params);
        $sql = $sql . " WHERE article_id=:article_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* Setea la disponibilidad enviando como parametro article_id y condition */
    public function set_available($article_id, $condition)
    {
        $params['article_id']           = $article_id;
        $params['article_available']    = $condition;
        $params['article_lastedit']     = now();

        $sql = "UPDATE `articles` SET ";
        $sql    = generate_bind($sql, $params);
        $sql = $sql . " WHERE article_id=:article_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
