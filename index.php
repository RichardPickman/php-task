<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, DELETE");
    header('Content-Type: application/json; charset=utf-8');

    require_once './classes/Product.php';
    require_once './classes/Db.php';
    require_once './classes/ProductMapper.php';
    require_once './classes/DVD.php';
    require_once './classes/Book.php';
    require_once './classes/Furniture.php';

    class DataRouter {
        public static function routedata($data) {
            $type = $data['type'];

            $productTypes = [
                'book' => Book::class,
                'dvd' => DVD::class,
                'furniture' => Furniture::class,
            ];

            $product = new $productTypes[$type]($data);

            $mapper = new ProductMapper();

            $mapper->getAll();

            if ($product->isProductExist($product->sku)) {
                http_response_code(400);
                $response = array(
                    "message" => "Item with provided sku already exist",
                );

                echo json_encode($response);

                return;
            }

            if ($product instanceof DataBase) {
                $product->save();
            }

            echo json_encode("2");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = file_get_contents('php://input');
        $decodedData = json_decode($postData, true);

        DataRouter::routedata($decodedData);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $mapper = new ProductMapper();

        $payload = $mapper->getAll();

        echo json_encode($payload);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $postData = file_get_contents('php://input');
        $decodedData = json_decode($postData, true);

        $mapper = new ProductMapper();

        foreach($decodedData as $data) {
            $mapper->deleteById($data);
        }

        $response = array(
            "message" => "Items successfully removed",
        );

        echo json_encode($response);
    }
?>
