<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Tax extends HTTP {
    
    function getTaxes( UserAuth $userauth, int $page = 0, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'taxapi' ) .'/readtaxes?page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getTax( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'taxapi' ) .'/readtaxbyid?taxid=' . $id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function addTaxes( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'taxapi' ) .'/addtaxes';
        return  $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function deleteTax( UserAuth $userauth, $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'taxapi' ) .'/deletetax/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'taxapi' ) . '/deleteall';
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
}



