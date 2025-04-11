<?php
/** 
 * Tabla: articles_recipe
 * Asociacion de articulos entre si. Depende, en este caso, de las tablas articles_food,articles_category,categories
 * */

class articles_associatedModel extends Model
{
    public $article_associate_primary;
    public $article_associate_secondary;

    public function add()
    {
        $sql    = "INSERT INTO `articles_associated` SET ";
        $params =
            [
                'article_associate_primary'    => $this->article_associate_primary,
                'article_associate_secondary'  => $this->article_associate_secondary,
            ];
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        $sql    = "SELECT article_associate_secondary FROM `articles_associated` WHERE  article_associate_primary=:article_associate_primary";
        $params =
            [
                'article_associate_primary' => $this->article_associate_primary,
            ];
        $sql    = generate_bind($sql, $params);
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update()
    {
        $sql    = "UPDATE `articles_associated` SET ";
        $params =
            [
                'article_associate_primary'    => $this->article_associate_primary,
                'article_associate_secondary'  => $this->article_associate_secondary,
            ];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE article_associate_primary=:article_associate_primary";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete_all()
    {
        $sql    = "DELETE FROM `articles_associated` WHERE article_associate_primary=:article_associate_primary";
        $params = ['article_associate_primary' => $this->article_associate_primary];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** En esta funcion ya la devolvemos formateada porque solo se usara de consulta */
    public function association_listed()
    {
        $sql = "SELECT 
                category_name,
                category_abbreviation,

                food_article,
                food_picture_front,
                food_name,
                food_subname,
                food_metadata

                FROM `articles_food`
                INNER JOIN `articles` ON article_id=food_article
                INNER JOIN `articles_category` ON article_category_bound=food_article
                INNER JOIN `categories` ON category_id=article_category_id
                WHERE article_condition>0 AND article_available>0";

        try {
            $data = parent::query($sql);
            if ($data != []) {
                foreach ($data as $i => $v) {
                    $data[$i]['food_picture_front'] = IMAGES . $v['food_picture_front'];
                }
            }
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
