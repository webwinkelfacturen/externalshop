<?php

namespace Externalshop\Processor;

use Externalshop\Model\UserAuth;

class Processor {

    protected $user;

    function __construct($wistclientid, $wistclientsecret) {
        $this->user = new UserAuth($wistclientid, $wistclientsecret);
    }
        
}
