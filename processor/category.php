<?php

namespace Externalshop\Processor;

use Externalshop\Processor\Processor;
use Externalshop\System\HTTP\Category as CategoryHTTP;

class Category extends Processor {

    function readCategories():array {
        $http = new CategoryHTTP();
        return json_decode($http->getCategories($this->user), true);
    }

    function add(array $array):array {
        $http  = new CategoryHTTP();
        return json_decode($http->add($this->user, ['categories' => json_encode($array)]), true);
    }

    function delete(string $jsoncategoryid):array {
        $http = new CategoryHTTP();
        return json_decode($http->deleteCategory($this->user, $jsoncategoryid), true);
    }

    function deleteAll():array {
        $http = new CategoryHTTP();
        return json_decode($http->deleteAll($this->user), true);
    }

}
