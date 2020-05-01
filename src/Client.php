<?php

namespace Yess;

class Client
{
        private $service;

        public function __construct($addr, $port, $user = NULL, $pwd = NULL)
        {
                $opts = [];
                $channel = NULL;
                $host = "$addr:$port";
                $opts['credentials'] = \Grpc\ChannelCredentials::createInsecure();
                $service = new YessServiceClient($host, $opts, $channel);
                $timout = 3000000; // 3 sec
                $service->waitForReady($timout);
                $this->service = $service;
        }

        private function checkMeta($meta)
        {
                if ($meta->code == 0) {
                        return;
                }
                
                $grpc_codes = [
                        0 => 'OK',
                        1 => 'CANCELLED',
                        2 => 'UNKNOWN',
                        3 => 'INVALID_ARGUMENT',
                        4 => 'DEADLINE_EXCEEDED',
                        5 => 'NOT_FOUND',
                        6 => 'ALREADY_EXISTS',
                        7 => 'PERMISSION_DENIED',
                        8 => 'RESOURCE_EXHAUSTED',
                        9 => 'FAILED_PRECONDITION',
                        10 => 'ABORTED',
                        11 => 'OUT_OF_RANGE',
                        12 => 'UNIMPLEMENTED',
                        13 => 'INTERNAL',
                        14 => 'UNAVAILABLE',
                        15 => 'DATA_LOSS',
                        16 => 'UNAUTHENTICATED'
                ];
                $details = '[' . $grpc_codes[$meta->code] . '] ' . $meta->details;
                throw new YessException($details, $meta->code);
        }

        private function checkStatus(ResponseStatus $status)
        {
                if ($status->getStatus() > 0)
                        throw new YessException($status->getMsg());
        }

        public function createStream($type)
        {
                $req = new CreateStreamReq(compact('type'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->createStream($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
        }

        public function getAllStreams()
        {
                $req = new GetAllStreamsReq();
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->getAllStreams($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $streams = $data->getStreams();
                $res = [];
                foreach ($streams as $s) {
                        $res[] = new Entity\Stream($s->getId(), $s->getType(), $s->getVersion());
                }
                return $res;
        }

        public function getStream($id)
        {
                $req = new GetStreamReq(compact('id'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->getStream($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $s = $data->getStream();
                if (!$s) {
                        return null;
                }
                $res = new Entity\Stream($s->getId(), $s->getType(), $s->getVersion());
                return $res;
        }

        public function getStreamsByType($type)
        {
                $req = new GetStreamsByTypeReq(compact('type'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->getStreamsByType($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $streams = $data->getStreams();
                $res = [];
                foreach ($streams as $s) {
                        $res[] = new Entity\Stream($s->getId(), $s->getType(), $s->getVersion());
                }
                return $res;
        }

        public function getEventsByStreamId($id)
        {
                $req = new GetEventsByStreamIdReq(compact('id'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->getEventsByStreamId($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $events = $data->getEvents();
                $res = [];
                foreach ($events as $e) {
                        $res[] = new Entity\Event($e->getId(), $e->getStreamId(), $e->getType(), $e->getPayload(), $e->getVersion());
                }
                return $res;
        }

        public function getEventsByStreamType($type)
        {
                $req = new GetEventsByStreamTypeReq(compact('type'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->getEventsByStreamType($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $events = $data->getEvents();
                $res = [];
                foreach ($events as $e) {
                        $res[] = new Entity\Event($e->getId(), $e->getStreamId, $e->getType(), $e->getPayload(), $e->getVersion());
                }
                return $res;
        }

        public function push($stream_id, $type, $payload, $version)
        {
                $data = compact('stream_id', 'type', 'payload', 'version');
                $event = new Event($data);
                $req = new PushEventReq(compact('event'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->PushEvent($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $status = $data->getStatus();
                $this->checkMeta($meta);
                $this->checkStatus($status);
        }

        public function getProjections($type = NULL)
        {
                $req = new GetProjectionsReq(compact('type'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->GetProjections($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $projs = [];
                if (!$data)
                        return $projs;
                $status = $data->getStatus();
                $this->checkMeta($meta);
                $this->checkStatus($status);
                foreach ($data->getProjections() as $p) {
                        $projs[] = new Entity\Projection($p->getId(), $p->getType(), $p->getData());
                }
                return $projs;
        }

        public function createProjection($data, $type = null)
        {
                $req = new CreateProjectionReq(compact('type', 'data'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->CreateProjection($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $status = $data->getStatus();
                $this->checkStatus($status);
        }

        public function deleteProjection($id)
        {
                $req = new DeleteProjectionReq(compact('id'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->DeleteProjection($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $status = $data->getStatus();
                $this->checkMeta($meta);
                $this->checkStatus($status);
        }

        public function play($projection_id, $stream_id, $stream_type = null, $initial_state = [])
        {
                $initial_state = json_encode($initial_state);
                $req = new PlayReq(compact('projection_id', 'stream_id', 'stream_type', 'initial_state'));
                $meta = [];
                $opts = [];
                $unaryCall = $this->service->Play($req, $meta, $opts);
                $res = $unaryCall->wait();
                list($data, $meta) = $res;
                $this->checkMeta($meta);
                $status = $data->getStatus();
                $this->checkStatus($status);
                $state = $data->getState();
                return $state;
        }
}