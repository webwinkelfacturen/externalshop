<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Paymentmethod as PaymentmethodHTTP;

class Paymentmethod extends Processor {

    function readPaymentmethods():array {
        $http = new PaymentmethodHTTP();
        return json_decode($http->getPaymentmethods($this->user), true);
    }

    function add(array $array):string {
        $http = new PaymentmethodHTTP();
        return $http->addPaymentmethods($this->user, ['paymentmethods' => json_encode($array)]);
    }

    function delete(string $jsontaxid):array {
        $http = new PaymentmethodHTTP();
        return json_decode($http->deletePaymentmethod($this->user, $jsontaxid), true);
    }

    function deleteAll():array {
        $http = new PaymentmethodHTTP();
        return json_decode($http->deleteAll($this->user), true);
    }

}
