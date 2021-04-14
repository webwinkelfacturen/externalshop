<?php

namespace Externalshop\Processor;

use Externalshop\Model\UserAuth;
use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Tax as TaxHTTP;

class Tax extends Processor {

    function readTaxes():string {
        $http     = new TaxHTTP();
        return $http->getTaxes($this->user);
    }

    function add(array $array):string {
        $http = new TaxHTTP();
        return $http->addTaxes($this->user, ['taxes' => json_encode($array)]);
    }

    function delete(string $jsontaxid):string {
        $http     = new TaxHTTP();
        return $http->deleteTax($this->user, $jsontaxid);
    }

    function deleteAll():string {
        $http     = new TaxHTTP();
        return $http->deleteAll($this->user);
    }
}
