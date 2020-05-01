<?php

namespace Yess\Entity;

class Projection
{
        public $id;
        public $type;
        public $data;

        public function __construct($id, $type, $data)
        {
                $this->id = $id;
                $this->type = $type;
                $this->data = $data;
        }
}