<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Receipt extends HTTP {
    
    function getReceipts( UserAuth $userauth, $startdate, $enddate, int $page = 1, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'receiptapi' ) .'/listreceipts?startdate=' . $startdate . '&enddate=' . $enddate . '&page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getReceipt( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'receiptapi' ) .'/readreceiptbyid?receiptid=' . $id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function add( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'receiptapi' ) . '/addreceipt';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function delete( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'receiptapi' ) . '/deletereceipt/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth, string $startdate, string $enddate ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'receiptapi' ) . '/deletereceipts';
        $data      = ['startdate' => $startdate, 'enddate' => $enddate];
        return $this->send_msg($userauth, $url, 'DELETE', $data, true);
    }

}
