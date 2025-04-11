<?php

class staffModel extends Model
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
    # persons_profile
    public $person_profile_id;
    public $person_profile_name;
    public $person_profile_text;
    public $person_profile_module;
    public $person_profile_condition;
    # persons_accesss
    public $person_access_bound;
    public $person_access_profile;
    public $person_access_store;
    # Variables especificas
    public $store_id;
    public $fecha_desde;
    public $fecha_hasta;
    public $queried_column;

    /* ================== STAFF - Requiere tabla persons ================== */
    public function staff_add()
    {
        $params['person_name']              = $this->person_name;
        $params['person_lastname']          = $this->person_lastname;
        $params['person_document']          = $this->person_document;
        $params['person_picture']           = $this->person_picture;
        $params['person_birthday']          = $this->person_birthday;
        $params['person_gender']            = $this->person_gender;
        $params['person_cellphone']         = $this->person_cellphone;
        $params['person_address']           = $this->person_address;
        $params['person_city']              = $this->person_city;
        $params['person_postalcode']        = $this->person_postalcode;
        $params['person_email']             = $this->person_email;
        $params['person_observation']       = $this->person_observation;
        $params['person_employee']          = $this->person_employee;
        $params['person_pass']              = $this->person_pass;
        $params['person_zone']              = $this->person_zone;
        $params['person_condition']         = $this->person_condition;
        $params['person_vip']               = $this->person_vip;
        $params['person_prefer_address']    = $this->person_prefer_address;
        $params['person_prefer_store']      = $this->person_prefer_store;
        $params['person_created']           = $this->person_created;

        $sql    = "INSERT INTO `persons` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function staff_update()
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
    public function staff_all()
    {
        $params['person_access_store'] = $this->store_id;
        $sql = "SELECT 
            person_id,
            person_name,
            person_lastname,
            person_picture,
            person_document,
            person_cellphone,
            person_employee,
            person_condition,
            person_condition_color,
            DATE_FORMAT(person_lastaccess, '%d/%m/%Y %H:%i') AS person_lastaccess,
            COALESCE(GROUP_CONCAT(person_access_profile ORDER BY person_access_profile ASC SEPARATOR ','), '') AS profiles_enabled
        FROM persons
        INNER JOIN persons_condition ON person_condition_id=person_condition
        LEFT JOIN persons_access ON person_access_bound=person_id AND person_access_store=:person_access_store
        WHERE person_employee = 1 AND person_condition >= 0 /* AND person_id != 1 */
        GROUP BY person_id, person_condition_color
        ORDER BY person_lastaccess DESC, person_lastedit DESC;";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function staff_one()
    {
        # En la misma consulta se puede buscar por id, documento o telefono
        $params = ['person_id' => $this->person_id];
        $sql    = "SELECT * FROM `persons` 
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
    public function staff_store()
    {
        $params['personstore'] = $this->store_id;
        $sql = "SELECT 
            person_id,
            person_name,
            person_lastname,
            person_picture,
            person_document,
            person_cellphone,
            person_employee,
            person_condition,
            person_condition_color,
            DATE_FORMAT(person_lastaccess, '%d/%m/%Y %H:%i') AS person_lastaccess
        FROM persons
        INNER JOIN persons_condition ON person_condition_id=person_condition
        GROUP BY person_id, person_condition_color
        ORDER BY person_lastaccess DESC, person_lastedit DESC;";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function staff_present()
    {
        $params['store_id'] = $this->store_id;
        $sql = "SELECT person_id,person_name,person_lastname,person_picture,person_document,person_condition,
        DATE_FORMAT(attendance_entry_date, '%d/%m/%Y %H:%i') AS attendance_entry_date,attendance_entry_ip,attendance_entry_agent
        FROM `persons` 
        INNER JOIN `attendances` ON attendance_person_id=person_id
        WHERE attendance_condition = 1 AND attendance_entry_store=:store_id 
        ORDER BY attendance_entry_date desc";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /* ================== PERFILES - Requiere tabla persons_profile ================== */
    public function profile_list($module = 'all')
    {
        $params = [];
        $sql = "SELECT 
                person_profile_id, person_profile_name, person_profile_text 
                FROM `persons_profile` 
                WHERE person_profile_condition !=0";
        if ($module != 'all') {
            $params['person_profile_module'] = $module;
            $sql = $sql . " AND person_profile_module=:person_profile_module";
        }
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function profile_selected()
    {
        $params['person_access_store']  = $this->person_access_store;
        $params['person_profile_name']  = $this->person_profile_name;

        $sql = "SELECT person_id,person_name,person_lastname,person_picture,person_document
        FROM `persons_access` 
        INNER JOIN `persons` ON person_id=person_access_bound
        INNER JOIN `persons_profile` ON person_profile_id=person_access_profile
        WHERE person_profile_name=:person_profile_name AND person_access_store=:person_access_store";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function profile_one()
    {
        $params['person_access_profile'] = $this->person_access_profile;
        $sql = "SELECT person_profile_name, person_profile_text 
        FROM `persons_profile` 
        WHERE person_profile_id=:person_access_profile";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /* ================== ACCESOS - Requiere tabla persons_access ================== */
    public function access_add()
    {
        $params['person_access_bound']     = $this->person_access_bound;
        $params['person_access_profile']   = $this->person_access_profile;
        $params['person_access_store']     = $this->person_access_store;

        $sql    = "INSERT INTO `persons_access` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function access_delete()
    {
        $params['person_access_bound']     = $this->person_access_bound;
        $params['person_access_store']     = $this->person_access_store;

        $sql = "DELETE FROM `persons_access` WHERE 
        person_access_bound=:person_access_bound 
        AND
        person_access_store=:person_access_store";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function access_check()
    {
        $params['person_access_bound']     = $this->person_access_bound;
        $params['person_access_profile']   = $this->person_access_profile;
        $params['person_access_store']     = $this->person_access_store;
        $sql = "SELECT person_access_bound FROM `persons_access` WHERE 
        person_access_bound=:person_access_bound 
        AND 
        person_access_profile=:person_access_profile 
        AND 
        person_access_store=:person_access_store 
        LIMIT 1";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
