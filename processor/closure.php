<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Closure as ClosureHTTP;

class Closure extends Processor {

    function readClosures($startdate, $enddate):array {
        $http = new ClosureHTTP();
        return json_decode($http->getClosures($this->user, $startdate, $enddate), true);
    }

    function add(string $json):array {
        $http = new ClosureHTTP();
        return json_decode($http->addClosure($this->user, json_decode($json, true)), true);
    }

    function delete(string $id):array {
        $http = new ClosureHTTP();
        return json_decode($http->deleteClosure($this->user, $id), true);
    }

}
