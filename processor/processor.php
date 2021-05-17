<?php

namespace Externalshop\Processor;

use Externalshop\Model\UserAuth;

class Processor {

    protected $user;
    protected $jsonencode;

    function __construct(string $wistclientid, string $wistclientsecret, bool $jsonencode = false) {
        $this->user = new UserAuth($wistclientid, $wistclientsecret);
        $this->jsonencode = $jsonencode;
    }
        
}
