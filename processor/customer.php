<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Customer as CustomerHTTP;

class Customer extends Processor {

    function readCustomers():array {
        $http = new CustomerHTTP();
        return json_decode($http->getCustomers($this->user), true);
    }

    function add(array $array):array {
        $http = new CustomerHTTP();
        return json_decode($http->add($this->user, ['customer' => json_encode($array)], $this->jsonencode), true);
    }

    function delete(string $id):array {
        $http     = new CustomerHTTP();
        return json_decode($http->delete($this->user, $id), true);
    }

}
