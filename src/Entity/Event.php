<?php

namespace Yess\Entity;

class Event
{
        public $id;
        public $stream_id;
        public $type;
        public $payload;
        public $version;

        public function __construct($id, $stream_id, $type, $payload, $version)
        {
                $this->id = $id;
                $this->stream_id = $stream_id;
                $this->type = $type;
                $this->payload = $payload;
                $this->version = $version;
        }
}