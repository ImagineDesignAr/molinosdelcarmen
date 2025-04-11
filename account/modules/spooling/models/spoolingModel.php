<?php

class spoolingModel extends Model
{
    # table spooler
    public $spooler_id;
    public $spooler_printer;
    public $spooler_store;
    public $spooler_order;
    public $spooler_type;
    public $spooler_condition;

    public function spooler_add()
    {
        $params['spooler_printer']      = $this->spooler_printer;
        $params['spooler_store']        = $this->spooler_store;
        $params['spooler_order']        = $this->spooler_order;
        $params['spooler_type']         = $this->spooler_type;
        $params['spooler_condition']    = $this->spooler_condition;

        $sql = "INSERT INTO `spooler` SET ";
        $sql = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function spooler_delete()
    {
        $params['spooler_id'] = $this->spooler_id;
        $sql = "DELETE FROM `spooler` WHERE spooler_id=:spooler_id LIMIT 1";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function spooler_empty()
    {
        $params['spooler_store'] = $this->spooler_store;
        $sql    = "DELETE FROM `spooler` WHERE spooler_store=:spooler_store";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function spooler_pending()
    {
        $params['spooler_printer'] = $this->spooler_printer;
        $sql    = "SELECT * FROM `spooler` WHERE spooler_printer=:spooler_printer AND spooler_condition = 0";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function spooler_process($spooler_id)
    {
        $sql = "UPDATE `spooler` SET spooler_condition=1 WHERE spooler_id=$spooler_id";
        try {
            return (parent::query($sql)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    # table stores_printer
    public $store_printer_id;
    public $store_printer_bound;
    public $store_printer_name;
    public $store_printer_initial_height;
    public $store_printer_left_margin;
    public $store_printer_leading;
    public $store_printer_width;
    public $store_printer_font;
    public $store_printer_condition;
    public function printer_default()
    {
        $params['spooler_store'] = $this->spooler_store;
        $sql = "SELECT * FROM `stores_printer` WHERE store_printer_bound=:spooler_store AND store_printer_condition = 1 LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function printer_one()
    {
        $params['spooler_printer'] = $this->spooler_printer;
        $sql = "SELECT * FROM `stores_printer` WHERE store_printer_id=:spooler_printer AND store_printer_condition = 1 LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function printer_list()
    {
        $sql = "SELECT 
        store_printer_id,
        store_printer_name,
        store_printer_initial_height,
        store_printer_left_margin 
        store_printer_leading,
        store_printer_width,
        store_printer_font,
        store_printer_condition,
        store_type,
        store_name
        FROM `stores_printer`
        INNER JOIN `stores` ON store_id = store_printer_bound";
        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
