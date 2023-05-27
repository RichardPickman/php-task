<?php

require_once './classes/Db.php';

abstract class AbstractProduct extends DataBase {
    private $sku;
    private $name;
    private $price;
    private $type;

    public function __construct($args) {
        parent::__construct();
        
        $this->sku = $args['sku'];
        $this->name = $args['name'];
        $this->price = $args['price'];
        $this->type = $args['type'];
    }

    public function saveBaseValues($type) {
        $sql = "INSERT INTO Product (sku, name, price, type) VALUES (:sku, :name, :price, :type)";
        $stmt = $this-> getDb() -> prepare($sql);
        
        $stmt->bindValue(':sku', $this->sku);
        $stmt->bindValue(':name', $this->name);
        $stmt->bindValue(':price', $this->price);
        $stmt->bindValue(':type', $type);
        
        $stmt->execute();
    }

    public function saveAttributes($attributes) {
        $query = "INSERT INTO Product_Attributes (sku, attribute_name, attribute_value) VALUES ";

        foreach ($attributes as $key => $value) {
            $query .= "('{$this->sku}', '{$key}', '{$value}'), ";
        }

        $query = rtrim($query, ', ');

        $stmt = $this-> getDb() -> prepare($query);

        $stmt->execute();
    }

    public function isProductExist() {
        $sql = "SELECT COUNT(*) AS product_count FROM Product WHERE sku=:sku";
        $stmt = $this -> getDb() -> prepare($sql);
        $stmt->bindValue(':sku', $this->sku);

        $stmt->execute();

        $result = $stmt -> fetch();

        return $result['product_count'] > 0;
    }

    abstract public function save();

    abstract public function deleteFromDatabase();

    public function __get($property) {
        return $this->{$property};
    }

    public function __set($property, $value) {
        $this->{$property} = $value;
    }
}

?>
