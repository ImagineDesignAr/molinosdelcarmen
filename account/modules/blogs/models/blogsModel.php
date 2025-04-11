<?php

class blogsModel extends Model
{
    public $blog_id;
    public $blog_date;
    public $blog_picture;
    public $blog_title;
    public $blog_summary;
    public $blog_content;
    public $blog_link;
    public $blog_person;
    public $blog_store;
    public $blog_condition;

    public function blog_add()
    {
        $params['blog_date']        = $this->blog_date;
        $params['blog_picture']     = $this->blog_picture;
        $params['blog_title']       = $this->blog_title;
        $params['blog_summary']     = $this->blog_summary;
        $params['blog_content']     = $this->blog_content;
        $params['blog_link']        = $this->blog_link;
        $params['blog_person']      = $this->blog_person;
        $params['blog_store']       = $this->blog_store;
        $params['blog_condition']   = $this->blog_condition;

        $sql = "INSERT INTO `blogs` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_update()
    {
        $params['blog_date']        = $this->blog_date;
        $params['blog_picture']     = $this->blog_picture;
        $params['blog_title']       = $this->blog_title;
        $params['blog_summary']     = $this->blog_summary;
        $params['blog_content']     = $this->blog_content;
        $params['blog_link']        = $this->blog_link;
        $params['blog_person']      = $this->blog_person;
        $params['blog_condition']   = $this->blog_condition;
        $params['blog_id']          = $this->blog_id;

        $sql    = "UPDATE `blogs` SET ";
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE blog_id=:blog_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_delete()
    {
        $params['blog_id'] = $this->blog_id;
        $sql = "DELETE FROM `blogs` WHERE blog_id=:blog_id LIMIT 1";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_view()
    {
        $sql = "SELECT * FROM `blogs` WHERE blog_id=:blog_id LIMIT 1";
        $params = ['blog_id' => $this->blog_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_list()
    {
        $sql = "SELECT * FROM `blogs` WHERE blog_condition != -1";
        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_all()
    {
        $sql = "SELECT * FROM `blogs` WHERE blog_condition=1";
        $params = [];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_last3()
    {
        $sql = "SELECT * FROM `blogs` WHERE blog_condition=1 ORDER BY blog_date asc LIMIT 3";
        $params = [];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function blog_condition($_id, $_condition)
    {
        $params['_id']          = $_id;
        $params['_condition']   = $_condition;
        $sql = "UPDATE `blogs` SET blog_condition=:_condition WHERE blog_id=:_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
