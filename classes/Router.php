<?php

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
