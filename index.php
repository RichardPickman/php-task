<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTION, DELETE");
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Type: text/plain charset=UTF-8');
    header('Content-Length: 0');
    
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
                'Book' => Book::class,
                'DVD' => DVD::class,
                'Furniture' => Furniture::class,
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
