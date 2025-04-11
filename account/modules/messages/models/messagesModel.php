<?php

class messagesModel extends Model
{
    public $message_id;
    public $message_name;
    public $message_email;
    public $message_subject;
    public $message_content;
    public $message_date;
    public $message_condition;

    public function message_add()
    {
        $params['message_name']         = $this->message_name;
        $params['message_email']        = $this->message_email;
        $params['message_subject']      = $this->message_subject;
        $params['message_content']      = $this->message_content;
        $params['message_date']         = $this->message_date;
        $params['message_condition']    = $this->message_condition;

        $sql = "INSERT INTO `messages` SET ";
        $sql = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function message_delete()
    {
        $params['message_id'] = $this->message_id;
        $sql = "DELETE FROM `messages` WHERE message_id=:message_id LIMIT 1";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function message_view()
    {
        $params['message_id'] = $this->message_id;
        $sql = "SELECT * FROM `messages` WHERE message_id=:message_id LIMIT 1";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function message_list()
    {
        $sql = "SELECT message_id,message_name,message_email,message_subject,message_content,message_condition,
        DATE_FORMAT(message_date, '%d/%m/%Y %H:%i') AS message_date FROM `messages` WHERE message_condition!=-1";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function message_condition($_id, $_condition)
    {
        $params['_id']          = $_id;
        $params['_condition']   = $_condition;
        $sql = "UPDATE `messages` SET message_condition=:_condition WHERE message_id=:_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
