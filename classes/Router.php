<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, OPTION, DELETE");
    header('Content-Type: application/json; charset=utf-8');

    class Router {
        private $routes;

        public function __construct($routes) {
            $this->routes = $routes;
        }

        public function handleRequest($method) {
            $postData = file_get_contents('php://input');
            $decodedData = json_decode($postData, true);

            if (isset($this->routes[$method])) {
                $handler = $this->routes[$method];

                if (is_callable($handler)) {
                    $handler($decodedData);

                    return;
                }
            }
            
            echo json_encode([ "message" => "Method not supported" ]);
        }
    }
?>
