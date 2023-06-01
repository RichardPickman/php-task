<?php

    require_once __DIR__ . '/Product.php';

    class Book extends AbstractProduct {
        protected $weight;

        public function __construct($args) {
            parent::__construct($args);

            $this->weight = $args['weight'];
        }

        public function save() {
            $this->saveBaseValues($this->type);
            $this->saveAttributes(get_object_vars($this));
        }

        public function deleteFromDatabase() {
            
        }
    }

?>
