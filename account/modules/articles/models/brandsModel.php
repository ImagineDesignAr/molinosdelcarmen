<?php

class brandsModel extends Model
{
    public $brand_id;
    public $brand_class;
    public $brand_subcategory;
    public $brand_name;
    public $brand_abbreviation;
    public $brand_condition;

    public function add()
    {
        $params['brand_class']          = $this->brand_class;
        $params['brand_subcategory']    = $this->brand_subcategory;
        $params['brand_name']           = $this->brand_name;
        $params['brand_abbreviation']   = $this->brand_abbreviation;
        $params['brand_condition']      = $this->brand_condition;

        $sql = "INSERT INTO `brands` SET ";
        $sql = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete()
    {
        $params['brand_id'] = $this->brand_id;
        $sql = "DELETE FROM `brands` WHERE brand_id=:brand_id LIMIT 1";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        $sql = "SELECT * FROM `brands` WHERE brand_condition=1";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exists()
    {
        $params['brand_name'] = $this->brand_name;
        $sql = "SELECT brand_id FROM `brands` WHERE brand_name=:brand_name";

        try {
            return (parent::query($sql, $params, true));
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function add_ifnotexist($brand_class)
    {
        # Comprueba que la marca no exista
        $params['brand_name'] = $this->brand_name;
        $sql = "SELECT brand_id FROM `brands` WHERE LOWER(brand_name)=LOWER(:brand_name)";
        try {
            $brand_id = parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
        # Si existe devuelve el brand_id
        if ($brand_id != []) {
            return $brand_id['brand_id'];
        }
        # Si no existe, la crea y devuelve el brand_id
        $params['brand_class']          = $brand_class;
        $params['brand_subcategory']    = null;
        $params['brand_name']           = $this->brand_name;
        $params['brand_abbreviation']   =  replace_chars($this->brand_name);
        $params['brand_condition']      = 1;

        $sql = "INSERT INTO `brands` SET ";
        $sql = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
