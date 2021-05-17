<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\HTTP;
use Externalshop\System\Utils\Constants;

class Category extends HTTP {
    
    function getCategories( UserAuth $userauth, int $page = 1, int $count = 10 ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'categoryapi' ) .'/listcategories?page=' . $page . '&count=' . $count;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function getCategory( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'categoryapi' ) . '/listCategoryById?categoryid=' . $id;
        return $this->send_msg( $userauth, $url, 'GET', [], true );
    }

    function add( UserAuth $userauth, array $data ):string {
        $constants = new Constants();
        $url = $constants->getEndpointurl( 'categoryapi' ) . '/addcategories';
        return $this->send_msg($userauth, $url, 'POST', $data, true);
    }

    function delete( UserAuth $userauth, string $id ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'categoryapi' ) .'/deletecategory/' . $id;
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
    function deleteAll( UserAuth $userauth ):string {
        $constants = new Constants();
        $url       = $constants->getEndpointurl( 'categoryapi' ) . '/deletecategories';
        return $this->send_msg($userauth, $url, 'DELETE', [], true);
    }
    
}



