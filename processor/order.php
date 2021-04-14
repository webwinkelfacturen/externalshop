<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Order as OrderHTTP;

class Order extends Processor {

    function readOrders(string $startdate, string $enddate):array {
        $http = new OrderHTTP();
        return json_decode($http->getOrders($this->user, $startdate, $enddate), true);
    }

    function add(array $array):array {
        $http = new OrderHTTP();
        return json_decode($http->add($this->user, ['order' => json_encode($array)]), true);
    }

    function deleteAll(string $startdate, string $enddate):array {
        $http = new OrderHTTP();
        return json_decode($http->deleteAll($this->user, $startdate, $enddate), true);
    }

    function delete(string $id):array {
        $http = new OrderHTTP();
        return json_decode($http->delete($this->user, $id), true);
    }

}
