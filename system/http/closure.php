<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Closure extends HTTP {
    
    function getClosures( UserAuth $userauth, string $startdate, string $enddate, int $page = 1, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'closureapi' ) .'/searchclosures?startdate=' . $startdate . '&enddate=' . $enddate . '&page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getClosure( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'closureapi' ) . '/readclosurebyid?closureid='.$id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function add( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'closureapi' ) . '/addclosure';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function delete( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'closureapi' ) . '/deleteclosure/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth, string $startdate, string $enddate ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'closureapi' ) . '/deleteclosures';
        $data      = ['startdate' => $startdate, 'enddate' => $enddate];
        return $this->send_msg($userauth, $url, 'DELETE', $data, true);
    }

}
