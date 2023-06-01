<?php

    require_once __DIR__ . '/Product.php';

    class DVD extends AbstractProduct {
        protected $size;

        public function __construct($args) {
            parent::__construct($args);

            $this->size = $args['size'];
        }

        public function save() {
            $this->saveBaseValues($this->type);
            $this->saveAttributes(get_object_vars($this));
        }

        public function deleteFromDatabase() {
            
        }
    }

?>
