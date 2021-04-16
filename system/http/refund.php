<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Refund extends HTTP {
    
    function getRefunds( UserAuth $userauth, $startdate, $enddate, int $page = 1, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'refundapi' ) .'/listrefunds?startdate=' . $startdate . '&enddate=' . $enddate . '&page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getRefund( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'refundapi' ) .'/readrefundbyid?refundid='.$id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function add( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'refundapi' ) .'/addrefund';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function delete( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'refundapi' ) .'/deleterefund/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth, string $startdate, string $enddate ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'refundapi' ) . '/deleterefunds';
        $data      = ['startdate' => $startdate, 'enddate' => $enddate];
        return $this->send_msg($userauth, $url, 'DELETE', $data, true);
    }

}
