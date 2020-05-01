<?php

namespace Yess\Entity;

class Stream
{
        public $id;
        public $type;
        public $version;

        public function __construct($id, $type, $version)
        {
                $this->id = $id;
                $this->type = $type;
                $this->version = $version;
        }

        public function getEvents() {
                return [];
        }
}