<?php
/* Modelo base */
class nullModel extends Model
{
    public $null_id;
    public $null_condition;

    public function add()
    {
        $sql    = "INSERT INTO `null` SET ";
        $params = ['null_condition' => $this->null_condition];
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update()
    {
        $sql    = "UPDATE `null` SET ";
        $params = ['null_condition' => $this->null_condition,'null_id' => $this->null_id];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE null_id=:null_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete()
    {
        $sql    = "DELETE FROM `null` WHERE null_id=:null_id LIMIT 1";
        $params = ['null_id' => $this->null_id];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function one()
    {
        $sql = "SELECT * FROM `null` WHERE null_id=:null_id LIMIT 1";
        $params = ['null_id' => $this->null_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        $sql = "SELECT * FROM `null` ";
        $params = [];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function status_change($status_id, $status_column)
    {
        $sql = "UPDATE `null` SET $status_column=$status_id WHERE null_id=:null_id";
        $params = ['null_id' => $this->null_id];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
