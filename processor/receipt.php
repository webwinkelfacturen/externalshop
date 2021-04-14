<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Receipt as ReceiptHTTP;

class Receipt extends Processor {

    function readReceipts($startdate, $enddate):array {
        $http = new ReceiptHTTP();
        return json_decode($http->getReceipts($this->user, $startdate, $enddate), true);
    }

    function add(array $array):array {
        $http = new ReceiptHTTP();
        return json_decode($http->add($this->user, ['receipt' => json_encode($array)]), true);
    }

    function deleteAll(string $startdate, string $enddate):array {
        $http = new OrderHTTP();
        return json_decode($http->deleteAll($this->user, $startdate, $enddate), true);
    }

    function delete(string $id):array {
        $http = new ReceiptHTTP();
        return json_decode($http->delete($this->user, $id), true);
    }

}
