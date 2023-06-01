<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
    header('Content-Type: application/json; charset=utf-8');
    
    require_once './classes/Router.php';
    require_once './classes/entities/Book.php';
    require_once './classes/entities/DVD.php';
    require_once './classes/entities/Furniture.php';
    require_once './classes/ProductMapper.php';

    $method = $_SERVER['REQUEST_METHOD'];
    $router = new Router([
        "GET" => function() {
            $mapper = new ProductMapper();

            $payload = $mapper->getAll();

            echo json_encode($payload);
        },
        "POST" => function($data) {
            $type = $data['type'];

            $productTypes = [
                'book' => Book::class,
                'dvd' => DVD::class,
                'furniture' => Furniture::class,
            ];

            $product = new $productTypes[$type]($data);

            $mapper = new ProductMapper();

            if ($product->isProductExist($product->sku)) {
                http_response_code(400);
                $response = ["message" => "Item with provided sku already exist"];

                echo json_encode($response);

                return;
            }

            if ($product instanceof AbstractProduct) {
                $product->save();
            }

            $response = [
                "message" => "Item successfully created"
            ];

            echo json_encode($response);
        },
        "DELETE" => function($data) {
            $mapper = new ProductMapper();

            $mapper->deleteById($data);

            $response = array(
                "message" => "Items successfully removed",
            );

            echo json_encode($response);
        }
    ]);
    
    $router -> handleRequest($method);
?>
