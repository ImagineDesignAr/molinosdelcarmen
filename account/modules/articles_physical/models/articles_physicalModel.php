<?php
# Update 02/10/2024
class articles_physicalModel extends Model
{
    public $physical_id;
    public $physical_article;
    public $physical_picture;
    public $physical_title;
    public $physical_name;
    public $physical_barcode;
    public $physical_sku;
    public $physical_presentation;
    public $physical_description;
    public $physical_attribute;
    public $physical_measure_id;
    public $physical_maxsale;
    public $physical_minsale;
    public $physical_metadata;
    public $physical_automatic_price;
    public $physical_cost;
    public $physical_tax;
    public $physical_utility;
    public $physical_price;
    public $physical_offer_price;
    public $physical_local;
    public $physical_web;
    public $physical_sunday;
    public $physical_monday;
    public $physical_tuesday;
    public $physical_wednesday;
    public $physical_thursday;
    public $physical_friday;
    public $physical_saturday;
    public $physical_brand_id;
    public $physical_condition;

    /** Trae todas las articulos solo filtrando los "eliminados"  */
    public function physical_list()
    {
        $sql = "SELECT 
                article_class,
                article_condition,
                article_available,

                category_id,
                category_name,

                physical_article,
                physical_picture,
                physical_title,physical_presentation,physical_attribute,physical_description,
                physical_metadata,physical_sku,physical_barcode,physical_sku,
                physical_price,physical_offer_price,
                physical_brand_id,
                physical_condition,
                brand_name,
                measure_name,
                article_condition_name,article_condition_color

                FROM `articles_physical`
                INNER JOIN `articles` ON article_id=physical_article
                INNER JOIN `articles_category` ON article_category_bound=physical_article
                INNER JOIN `articles_condition` ON article_condition_id=physical_condition
                INNER JOIN `categories` ON category_id=article_category_id
                INNER JOIN `brands` ON brand_id=physical_brand_id
                INNER JOIN `measures` ON measure_id=physical_measure_id
                WHERE article_condition!=-1";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function physical_front()
    {
        $sql = "SELECT 
                physical_article,
                physical_picture,
                physical_title,physical_presentation,physical_attribute,physical_description,
                physical_metadata,physical_sku,physical_barcode,
                physical_price,physical_offer_price,
                physical_brand_id,
                brand_name,
                measure_name,
                article_condition_name,article_condition_color

                FROM `articles_physical`
                INNER JOIN `articles` ON article_id=physical_article
                INNER JOIN `articles_category` ON article_category_bound=physical_article
                INNER JOIN `articles_condition` ON article_condition_id=physical_condition
                INNER JOIN `categories` ON category_id=article_category_id
                INNER JOIN `brands` ON brand_id=physical_brand_id
                INNER JOIN `measures` ON measure_id=physical_measure_id
                WHERE article_condition!=-1 AND article_available=1";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function physical_add()
    {
        $params['physical_article']         = $this->physical_article;
        $params['physical_picture']         = $this->physical_picture;
        $params['physical_title']           = $this->physical_title;
        $params['physical_barcode']         = $this->physical_barcode;
        $params['physical_sku']             = $this->physical_sku;
        $params['physical_presentation']    = $this->physical_presentation;
        $params['physical_description']     = $this->physical_description;
        $params['physical_attribute']       = $this->physical_attribute;
        $params['physical_measure_id']      = $this->physical_measure_id;
        $params['physical_metadata']        = $this->physical_metadata;
        #$params['physical_automatic_price'] = $this->physical_automatic_price;
        #$params['physical_cost']            = $this->physical_cost;
        #$params['physical_tax']             = $this->physical_tax;
        #$params['physical_utility']         = $this->physical_utility;
        #$params['physical_price']           = $this->physical_price;
        #$params['physical_offer_price']     = $this->physical_offer_price;
        $params['physical_local']           = $this->physical_local;
        $params['physical_web']             = $this->physical_web;
        #$params['physical_sunday']          = $this->physical_sunday;
        #$params['physical_monday']          = $this->physical_monday;
        #$params['physical_tuesday']         = $this->physical_tuesday;
        #$params['physical_wednesday']       = $this->physical_wednesday;
        #$params['physical_thursday']        = $this->physical_thursday;
        #$params['physical_friday']          = $this->physical_friday;
        #$params['physical_saturday']        = $this->physical_saturday;
        $params['physical_brand_id']        = $this->physical_brand_id;
        $params['physical_condition']       = $this->physical_condition;

        $sql    = "INSERT INTO `articles_physical` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function physical_update()
    {
        $params['physical_id']              = $this->physical_id;
        $params['physical_article']         = $this->physical_article;
        $params['physical_picture']         = $this->physical_picture;
        $params['physical_title']           = $this->physical_title;
        $params['physical_barcode']         = $this->physical_barcode;
        $params['physical_sku']             = $this->physical_sku;
        $params['physical_presentation']    = $this->physical_presentation;
        $params['physical_description']     = $this->physical_description;
        $params['physical_attribute']       = $this->physical_attribute;
        $params['physical_measure_id']      = $this->physical_measure_id;
        $params['physical_metadata']        = $this->physical_metadata;
        #$params['physical_automatic_price'] = $this->physical_automatic_price;
        #$params['physical_cost']            = $this->physical_cost;
        #$params['physical_tax']             = $this->physical_tax;
        #$params['physical_utility']         = $this->physical_utility;
        #$params['physical_price']           = $this->physical_price;
        #$params['physical_offer_price']     = $this->physical_offer_price;
        $params['physical_local']           = $this->physical_local;
        $params['physical_web']             = $this->physical_web;
        #$params['physical_sunday']          = $this->physical_sunday;
        #$params['physical_monday']          = $this->physical_monday;
        #$params['physical_tuesday']         = $this->physical_tuesday;
        #$params['physical_wednesday']       = $this->physical_wednesday;
        #$params['physical_thursday']        = $this->physical_thursday;
        #$params['physical_friday']          = $this->physical_friday;
        #$params['physical_saturday']        = $this->physical_saturday;
        $params['physical_brand_id']        = $this->physical_brand_id;
        $params['physical_condition']       = $this->physical_condition;

        $sql    = "UPDATE `articles_physical` SET ";
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE physical_id=:physical_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function physical_view()
    {
        $params['physical_article'] = $this->physical_article;

        $sql = "SELECT 
        article_class,
        article_condition,
        article_available,
        article_stock,

        physical_id,
        physical_article,
        physical_picture,
        physical_title,physical_presentation,physical_attribute,physical_description,
        physical_metadata,physical_sku,physical_barcode,
        physical_measure_id,
        physical_maxsale,
        physical_minsale,
        physical_automatic_price,physical_cost,physical_tax,physical_utility,physical_price,physical_offer_price,
        physical_local,physical_web,physical_sunday,physical_monday,physical_tuesday,physical_wednesday,physical_thursday,physical_friday,physical_saturday,
        physical_condition,
        physical_brand_id,
        brand_name,
        article_condition_name,article_condition_color,
        (SELECT GROUP_CONCAT(article_category_id) as category FROM `articles_category` WHERE article_category_bound=article_id) AS category

        FROM `articles_physical`
        INNER JOIN `articles` ON article_id=physical_article
        INNER JOIN `articles_category` ON article_category_bound=physical_article
        INNER JOIN `articles_condition` ON article_condition_id=physical_condition
        INNER JOIN `categories` ON category_id=article_category_id
        INNER JOIN `brands` ON brand_id=physical_brand_id
        WHERE physical_article=:physical_article";

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function drafts()
    {
        $sql = "SELECT *,
        (SELECT JSON_ARRAY(category_name) FROM `articles_category` 
        INNER JOIN `categories` ON category_id = article_category_id
        WHERE article_category_bound=article_id) AS category
        FROM `articles_physical` 
        INNER JOIN `articles` ON article_id=physical_article
        INNER JOIN `articles_category` ON article_category_bound=article_id
        INNER JOIN `brands` ON brand_id=physical_brand_id
        WHERE physical_condition=3";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function drafts_empty()
    {
        $sql = "DELETE FROM `articles_physical` WHERE physical_condition=3";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function all()
    {
        $sql = "SELECT * FROM `articles_physical`";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
