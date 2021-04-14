<?php

namespace Externalshop\Model;

class UserAuth {        

    private $clientid;
    private $clientsecret;

    function __construct(string $id, string $secret) {
        $this->clientid     = $id;
        $this->clientsecret = $secret;
    }

    function modify(array $array) {
        $this->clientid     = $array['clientid'];
        $this->clientsecret = $array['clientsecret'];
    }

    function getClientid() {
        return $this->clientid;
    }

    function getClientsecret() {
        return $this->clientsecret;
    }

}
