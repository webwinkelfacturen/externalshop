<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Closure as ClosureHTTP;

class Closure extends Processor {

    function readClosures($startdate, $enddate):array {
        $http = new ClosureHTTP();
        return json_decode($http->getClosures($this->user, $startdate, $enddate), true);
    }

    function add(array $array):array {
        $http = new ClosureHTTP();
        return json_decode($http->add($this->user, ['closure' => json_encode($array)]), true);
    }

    function delete(string $id):array {
        $http = new ClosureHTTP();
        return json_decode($http->delete($this->user, $id), true);
    }

    function deleteAll(string $startdate = '2020-01-01', string $enddate = '2222-01-01'):array {
        $http = new ClosureHTTP();
        return json_decode($http->deleteAll($this->user, $startdate, $enddate), true);
    }

}
