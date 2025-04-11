<?php

class articles_categoryModel extends Model
{
    public $article_category_id;
    public $article_category_bound;

    public function all()
    {
        $sql = "SELECT * FROM `articles_category` WHERE article_category_id=:article_category_id";
        $params = ['article_category_id' => $this->article_category_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function add()
    {
        $sql = "INSERT INTO `articles_category` SET ";

        $params =
            [
                'article_category_id'       => $this->article_category_id,
                'article_category_bound'  => $this->article_category_bound,
            ];
        $sql    = generate_bind($sql,$params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete_all()
    {
        $sql = "DELETE FROM `articles_category` WHERE article_category_bound=:article_category_bound";
        $params = ['article_category_bound' => $this->article_category_bound];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

}
