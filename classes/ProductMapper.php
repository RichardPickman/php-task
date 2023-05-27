<?php

require_once './classes/Db.php';

class ProductMapper {
    private $db;

    public function __construct() {
        $db = new DataBase();
        $this->db = $db->getDb();
    }

    public function getById($sku) { // return parsed product
        $sql = 'SELECT * FROM Product WHERE sku = :sku';
        $stmt = $this-> db -> prepare($sql);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    private function getProduct($sku) {
        $query = "SELECT t1.sku, t1.name, t1.price, t1.type, t2.attribute_name, t2.attribute_value 
                  FROM Product as t1
                  JOIN Product_Attributes as t2 ON t1.sku = t2.sku
                  WHERE t1.sku = :sku"; // LEFT JOIN INSTEAD OF JOIN
        $stmt = $this-> db -> prepare($query);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $object = array();
        // check joins
        if (!empty($result)) {
            foreach ($result as $row) {
                $sku = $row["sku"];
                $name = $row["name"];
                $price = $row["price"];
                $type = $row["type"];
                $attributeName = $row["attribute_name"];
                $attributeValue = $row["attribute_value"];
    
                if (!isset($object["sku"])) {
                    $object["sku"] = $sku;
                    $object["name"] = $name;
                    $object["price"] = $price;
                    $object["type"] = $type;
                }
    
                $object[$attributeName] = $attributeValue;
            }
        }

        return $object;
    }

    public function getAll() {
        // avoid a lot of queries to db
        $sql = 'SELECT sku FROM Product';
        $stmt = $this-> db -> query($sql);

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $payload = array();

        foreach($result as $row) {
            $payload[] = $this->getProduct($row->sku);
        }

        return $result;
    }

    public function deleteById($sku) {
        try {
            $query = "DELETE FROM Product WHERE sku = :sku";
            $stmt = $this-> db -> prepare($query);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();
        } catch (PDOExceptions $e) {
            http_response_code(500);
            $response = ["message" => "Error occured during deletion of a row"];
                

            echo json_encode($response); // return value not echo

            return;
        }
    }
}

?>
