<?php

class articles_stockModel extends Model
{
    public $article_stock_id;
    public $article_stock_article;
    public $article_stock_count;
    public $article_stock_min;
    public $article_stock_store;
    public $article_stock_condition;

    public function articles_stock_add()
    {
        $params['article_stock_article']    = $this->article_stock_article;
        $params['article_stock_store']      = $this->article_stock_store;
        $params['article_stock_count']      = $this->article_stock_count;
        $params['article_stock_min']        = $this->article_stock_min;
        $params['article_stock_condition']  = $this->article_stock_condition;

        $sql    = "INSERT INTO `articles_stock` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function article_stock_list() # v3
    {
        /** 
         * Esta consulta obtiene información detallada de artículos que tienen stock disponible, 
         * combinando datos de varias tablas relacionadas. 
         * Utiliza COALESCE para unificar los campos de alimentos y suministros, 
         * priorizando la información de alimentos cuando está disponible. 
         * Se incluyen datos como la imagen del artículo, nombre, subnombre, medida asociada, categoría 
         * y cantidad en stock. 
         * Los JOIN aseguran que solo se muestren los artículos con stock activo en una tienda específica, 
         * y se filtran los artículos con condiciones válidas, evitando duplicados con GROUP BY.
         */
        $params['article_stock_store'] = $this->article_stock_store;

        $sql = "SELECT 
                article_id,
                COALESCE(food_picture_front, supply_picture) AS article_picture,
                COALESCE(food_name, supply_name) AS article_name,
                COALESCE(food_subname, supply_subname) AS article_subname,
                measure_name,
                article_stock_count,
                category_id,
                category_name,
                (SELECT GROUP_CONCAT(
                JSON_OBJECT(
                    'stock_order_date',stock_order_date,
                    'stock_order_type',stock_order_type,
                    'stock_order_number',stock_order_number,
                    'stock_order_condition',stock_order_condition,
                    'measure_name',measure_name,
                    'stock_item_quantity',stock_item_quantity,
                    'stock_item_price',stock_item_price
                    ) SEPARATOR ',')
                    FROM `stocks_item`
                    INNER JOIN `stocks_order`   ON stock_order_id = stock_item_order
                    LEFT JOIN articles_food     ON food_article = stock_item_order
                    LEFT JOIN articles_supply   ON supply_article = stock_item_order
                    INNER JOIN `measures`       ON measure_id=COALESCE(food_measure, supply_measure)
					WHERE stock_item_article=article_id AND stock_order_destination=:article_stock_store) 
                AS stock_history
                FROM articles
                LEFT JOIN articles_food         ON food_article = article_id
                LEFT JOIN articles_supply       ON supply_article = article_id
                INNER JOIN articles_stock       ON article_stock_article = article_id
                INNER JOIN articles_category    ON article_category_bound = article_id
                INNER JOIN categories           ON category_id = article_category_id
                INNER JOIN measures             ON measure_id = COALESCE(food_measure, supply_measure)
                WHERE article_condition!=-1
                AND article_stock = 1
                AND article_stock_store=:article_stock_store
                GROUP BY article_id,
                food_picture_front,supply_picture,
                food_name,supply_name,
                food_subname,supply_subname,
                measure_name,
                article_stock_count,
                category_id;";
        try {
            /* Probaremos mandar formateado al controlador */
            $rs = parent::query($sql, $params);
            if ($rs != []) {
                foreach ($rs as $i => $v) {
                    $rs[$i]['article_picture']      = IMAGES . $v['article_picture'];
                    $rs[$i]['article_stock_count']  = number_format($v['article_stock_count'], 3, '.');
                    $rs[$i]['keywords']             = generate_keywords([$v['article_name'], $v['article_subname']]);
                    $rs[$i]['stock_history']        = to_array($v['stock_history']);
                }
            }
            return $rs;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /** Consulta y devuelve el stock actual de un articulo segun su sucursal */
    public function get_stock()
    {
        $params['article_stock_article']    = $this->article_stock_article;
        $params['article_stock_store']      = $this->article_stock_store;

        $sql = "SELECT article_stock_id,article_stock_count 
                FROM `articles_stock` 
                WHERE article_stock_article=:article_stock_article 
                AND article_stock_store=:article_stock_store 
                LIMIT 1";
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Establece un nuevo valor al stock. La operacion de suma o resta se realiza en el Controller */
    public function set_stock()
    {
        $params['article_stock_count']  = $this->article_stock_count;
        $params['article_stock_id']     = $this->article_stock_id;
        $sql = "UPDATE `articles_stock` 
                SET article_stock_count=:article_stock_count 
                WHERE article_stock_id=:article_stock_id";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Establece una nueva condicion sobre el articulo en dicha sucursal */
    public function set_condition()
    {
        $params['article_stock_condition']  = $this->article_stock_condition;
        $params['article_stock_id']         = $this->article_stock_id;
        $sql = "UPDATE `articles_stock` 
                SET article_stock_condition=:article_stock_condition 
                WHERE article_stock_id=:article_stock_id 
                LIMIT 1";
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
