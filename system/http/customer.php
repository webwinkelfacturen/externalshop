<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Customer extends HTTP {
    
    function getCustomers( UserAuth $userauth, int $page = 1, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'customerapi' ) .'/readcustomers?page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getCustomer( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'customerapi' ) .'/readcustomerbyid?customerid=' . $id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function add( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'customerapi' ) . '/addcustomer';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function delete( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'customerapi' ) . '/deletecustomer/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
}
