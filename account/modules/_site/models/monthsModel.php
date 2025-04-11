<?php

class monthsModel extends Model
{
    public $month_id;
    public $month_name;
    public $month_abr;

    public function one()
    {
        $sql = "SELECT month_name,month_abr FROM `months` WHERE month_id=:month_id LIMIT 1";
        $params = ['month_id' => $this->month_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        $sql = "SELECT * FROM `months`";
        $params = [];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
