<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Order extends HTTP {
    
    function getOrders( UserAuth $userauth, string $startdate, string $enddate, int $page = 0, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'orderapi' ) .'/listorders?startdate=' . $startdate . '&enddate=' . $enddate . '&page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getOrder( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'orderapi' ) .'/readorderbyid?orderid=' . $id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function add( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'orderapi' ) .'/addorder';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function delete( UserAuth $userauth, $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'orderapi' ) .'/deleteorder/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth, string $startdate, string $enddate ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'orderapi' ) .'/deleteorders';
        $data      = ['startdate' => $startdate, 'enddate' => $enddate];
        return $this->send_msg($userauth, $url, 'DELETE', $data, true);
    }
    
}
