<?php

class Furniture extends AbstractProduct {
    protected $width;
    protected $height;
    protected $length;

    public function __construct($args) {
        parent::__construct($args);

        $this->width = $args['width'];
        $this->height = $args['height'];
        $this->length = $args['length'];
    }

    public function save() {
        $this->saveBaseValues($this->type);
        $this->saveAttributes(get_object_vars($this));
    }

    public function deleteFromDatabase() {
        
    }
}

?>
