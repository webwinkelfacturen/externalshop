<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Invoice as InvoiceHTTP;

class Invoice extends Processor {

    function readInvoices(string $startdate, string $enddate):array {
        $http = new InvoiceHTTP();
        return json_decode($http->getInvoices($this->user, $startdate, $enddate), true);
    }

    function add(array $array):array {
        $http = new InvoiceHTTP();
        return json_decode($http->add($this->user, ['invoice' => json_encode($array)]), true);
    }

    function deleteAll(string $startdate, string $enddate):array {
        $http = new OrderHTTP();
        return json_decode($http->deleteAll($this->user, $id), true);
    }

    function delete(string $id):array {
        $http = new InvoiceHTTP();
        return json_decode($http->delete($this->user, $id), true);
    }

}
