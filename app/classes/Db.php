<?php

# Clase para administrar la base de datos. v1.0

class Db
{
    private $link;
    private $engine;
    private $host;
    private $name;
    private $user;
    private $pass;
    private $charset;

    /**
     * Constructor para nuestra clase
     */

    public function __construct()
    {
        $this->engine   = IS_LOCAL ? LDB_ENGINE     : DB_ENGINE;
        $this->host     = IS_LOCAL ? LDB_HOST       : DB_HOST;
        $this->name     = IS_LOCAL ? LDB_NAME       : DB_NAME;
        $this->user     = IS_LOCAL ? LDB_USER       : DB_USER;
        $this->pass     = IS_LOCAL ? LDB_PASS       : DB_PASS;
        $this->charset  = IS_LOCAL ? LDB_CHARSET    : DB_CHARSET;
        return $this;
    }

    /**
     * Metodo para abrir una conexion a la base de datos
     * @return mixed
     */

    private function connect()
    {
        try {
            $this->link = new PDO($this->engine . ':host=' . $this->host . ';dbname=' . $this->name . ';charset=' . $this->charset, $this->user, $this->pass);
            return $this->link;
        } catch (PDOException $e) {
            destroy(cookie_iD);
            json_response(550, null, sprintf('No hay conexion a la base de datos, error: %s', $e->getMessage()));
            die();
        }
    }

    /**
     * Metodo para hacer un query a la base de datos
     * 
     * @param string $sql
     * @param array $params
     * @return array 
     * 
     * */
    public static function query($sql, $params = [], $fetch = false)
    {

        $db     = new self();
        $link   = $db->connect();       # Muestra la conexion a la DB
        $link->beginTransaction();      # Ante cualquier error, checkpoint
        $query  = $link->prepare($sql);


        # Manejando errores en la query o la peticion

        if (!$query->execute($params)) {

            $link->rollBack();
            $error = $query->errorInfo();
            # index 0 es el tipo de error
            # index 1 es el codigo de error
            # index 2 es el mensaje de error al usuario
            throw new Exception($error[2]);
        }

        # SELECT / INSERT / UPDATE / DELETE / ALTER TABLE
        # Manejando el tipo de query

        if (strpos($sql, 'SELECT') !== false) {

            if ($fetch === true) {
                $rows = $query->fetch(PDO::FETCH_ASSOC);
            } else {
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            }
            if (!is_array($rows)) {
                $rows = [];
            }

            return $rows;
        } elseif (strpos($sql, 'INSERT') !== false) {
            $id = $link->lastInsertId();
            $link->commit();
            return $id;
        } elseif (strpos($sql, 'UPDATE') !== false) {
            $link->commit();
            return true;
        } elseif (strpos($sql, 'DELETE') !== false) {
            if ($query->rowCount() > 0) {
                $link->commit();
                return true;
            }
            $link->rollBack();
            return false; # Nada se borra
        } else {
            # ALTER TABLE / DROP TABLE
            $link->commit();
            return true;
        }
    }
    /**
     * Metodo para crear tablas
     * 
     * @param string $table_name
     * @param string $primary_key
     * @param array $table_fields 
     * 
     * */
    public static function make($table_name, $primary_key, $table_fields)
    {
        $db     = new self();
        $link   = $db->connect();

        # Verifica que el nombre de la tabla sea válido
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table_name)) {
            return "Nombre de tabla no válido";
        }

        # Verifica que la clave primaria esté presente en los campos
        if (!isset($table_fields[$primary_key])) {
            return "La clave primaria no está presente en los campos";
        }

        # Construye la sentencia SQL CREATE TABLE
        $fields = null;
        foreach ($table_fields as $name => $options) {
            $type   = $options['type'];
            $length = $options['length'];
            $option = $options['option'];
            if ($length) {
                $fields .= sprintf('%s %s(%s) %s, ', $name, $type, $length, $option);
            } else {
                $fields .= sprintf('%s %s %s, ', $name, $type, $option);
            }
        }

        try {
            # Quita la última coma y espacio en blanco
            $fields = rtrim($fields, ', ');
            $sql    = sprintf('CREATE TABLE IF NOT EXISTS `%s` (%s, PRIMARY KEY (%s)) COLLATE=`utf8mb4_bin`', $table_name, $fields, $primary_key);

            if (!$link->exec($sql)) {
                return "Tabla $table_name creada exitosamente!";
            }
            return "Error al crear tabla";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
