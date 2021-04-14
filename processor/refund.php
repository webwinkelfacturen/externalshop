<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Refund as RefundHTTP;

class Refund extends Processor {

    function readRefunds($startdate, $enddate):array {
        $http = new RefundHTTP();
        return json_decode($http->getRefunds($this->user, $startdate, $enddate), true);
    }

    function add(array $array):array {
        $http = new RefundHTTP();
        return json_decode($http->add($this->user, ['refund' => json_encode($array)]), true);
    }

    function delete(string $id):array {
        $http = new RefundHTTP();
        return json_decode($http->deleteRefund($this->user, $id), true);
    }

}
