<?php
class personModel extends Model
{
    public $person_id;
    public $person_name;
    public $person_lastname;
    public $person_pass;
    public $person_document;
    public $person_birthday;
    public $person_gender;
    public $person_picture;
    public $person_cellphone;
    public $person_address;
    public $person_city;
    public $person_postalcode;
    public $person_email;
    public $person_observation;
    public $person_condition;
    public $person_wallet;
    public $person_created;
    public $person_lastedit;
    public $person_lastaccess;
    public $person_lastorder;
    public $person_vip;
    public $person_prefer_address;
    public $person_prefer_store;
    public $person_zone;
    public $person_employee;

    public function add_fast()
    {
        $params['person_name']          = $this->person_name;
        $params['person_address']       = $this->person_address;
        $params['person_zone']          = $this->person_zone;
        $params['person_document']      = $this->person_document;
        $params['person_birthday']      = $this->person_birthday;
        $params['person_gender']        = $this->person_gender;
        $params['person_picture']       = $this->person_picture;
        $params['person_cellphone']     = $this->person_cellphone;
        $params['person_created']       = $this->person_created;
        $params['person_condition']     = $this->person_condition;
        $params['person_vip']           = $this->person_vip;
        $params['person_prefer_store']  = $this->person_prefer_store;
        $params['person_employee']      = $this->person_employee;

        $sql = "INSERT INTO `persons` SET ";
        $sql = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function update()
    {
        $params['person_id']            = $this->person_id;
        $params['person_document']      = $this->person_document;
        $params['person_picture']       = $this->person_picture;
        $params['person_name']          = $this->person_name;
        $params['person_lastname']      = $this->person_lastname;
        $params['person_birthday']      = $this->person_birthday;
        $params['person_gender']        = $this->person_gender;
        $params['person_address']       = $this->person_address;
        $params['person_city']          = $this->person_city;
        $params['person_postalcode']    = $this->person_postalcode;
        $params['person_email']         = $this->person_email;
        $params['person_employee']      = $this->person_employee;
        $params['person_observation']   = $this->person_observation;
        $params['person_lastedit']      = $this->person_lastedit;

        $sql    = "UPDATE `persons` SET ";
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE person_id=:person_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function update_fast()
    {
        $params['person_id'] = $this->person_id;
        $params['person_name'] = $this->person_name;
        $params['person_birthday'] = $this->person_birthday;
        $params['person_address'] = $this->person_address;
        $params['person_zone'] = $this->person_zone;

        $sql = "UPDATE `persons` SET ";
        $sql = generate_bind($sql, $params);
        $sql = $sql . " WHERE person_id=:person_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Busca la persona por documento */
    public function check_document()
    {
        $params['person_document'] = $this->person_document;
        $sql = "SELECT person_id FROM `persons` WHERE person_document=:person_document LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Busca la persona por telefono */
    public function check_telephone()
    {
        $params['person_cellphone'] = $this->person_cellphone;
        $sql = "SELECT person_id,person_name,person_cellphone,person_birthday,order_detail_address,order_detail_zone,person_condition 
        FROM `persons` WHERE person_cellphone=:person_cellphone LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Funcion para guardar el ultimo login de la persona */
    public function lastlogin()
    {
        $params['person_lastaccess']    = now();
        $params['person_id']            = $this->person_id;
        $sql = "UPDATE `persons` SET person_lastaccess=:person_lastaccess WHERE person_id=:person_id";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Funcion para guardar el ultimo acceso a ordenar de la persona */
    public function lastaccess()
    {
        $params['person_lastaccess'] = now();
        $params['person_id']         = $this->person_id;
        $sql = "UPDATE `persons` SET person_lastaccess=:person_lastaccess WHERE person_id=:person_id";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function person_delete()
    {
        $params = ['person_id' => $this->person_id];
        $sql = "DELETE FROM `persons` WHERE person_id=:person_id LIMIT 1";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function pass_update()
    {
        $params['person_pass']      = $this->person_pass;
        $params['person_id']        = $this->person_id;
        $params['person_lastedit']  = $this->person_lastedit;

        $sql    = "UPDATE `persons` SET ";
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE person_id=:person_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function person_one()
    {
        # En la misma consulta se puede buscar por id, documento o telefono
        $params = ['person_id' => $this->person_id];
        $sql = "SELECT * FROM `persons` 
        INNER JOIN `persons_condition` ON person_condition_id = person_condition
        WHERE person_id=:person_id 
        OR person_document=:person_id 
        OR person_cellphone=:person_id 
        LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function person_all()
    {
        $sql = "SELECT person_id,person_name,person_lastname,person_picture,person_document,person_cellphone,person_address,person_vip,person_condition,person_condition_color 
        FROM `persons` 
        INNER JOIN `persons_condition` ON person_condition_id=person_condition
        WHERE person_condition >= 1 
        ORDER BY person_lastaccess desc,person_lastedit desc LIMIT 2000";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function person_condition()
    {
        $params['person_id']        = $this->person_id;
        $params['person_condition'] = $this->person_condition;
        $params['person_lastedit']  = $this->person_lastedit;

        $sql = "UPDATE `persons` SET 
        person_condition=:person_condition, 
        person_lastedit=:person_lastedit 
        WHERE person_id=:person_id";

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
