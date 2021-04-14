<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Paymentmethod extends HTTP {
    
    function getPaymentmethods( UserAuth $userauth, int $page = 1, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'paymentmethodapi' ) .'/readpaymentmethods?page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getPaymentmethod( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'paymentmethodapi' ) .'/readpaymentmethodbyid?paymentmethodid=' . $id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function addPaymentmethods( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'paymentmethodapi' ) .'/addpaymentmethods';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function deletePaymentmethod( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'paymentmethodapi' ) .'/deletepaymentmethod/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'paymentmethodapi' ) .'/deleteall';
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
}
