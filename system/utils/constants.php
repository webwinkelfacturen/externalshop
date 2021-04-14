<?php

namespace Externalshop\System\Utils;

class Constants {

    function getClientid(string $system = '') {
        return 'tk-2X6bg8JJSMe7qkYaDkd5hSDFPLSLXI5l9V9SS';
        return '.ML-vKZtWm2OIXmX1kiiEJBf3Vzhi7InpqJDCf6Q';
    }

    function getClientsecret(string $system = '') {
        return 'EOTTBU5tf57pQterbFdXOW4ph';
        return 'Ba63m23CzG9QJsoAw-V6E.uQ-';
    }

    function getEndpointurl(string $file = '') {
        return 'https://wisteria.webwinkelfacturen.nl/' . $file;
    }

    function getWisteriaOAuthurl(string $system = '') {
        return 'https://wisteria.webwinkelfacturen.nl/api';
    }

    function getCallbackurl(string $system = '') {
        return 'https://secure136.cloudinvoice.company/api/v2/cloudinvoice/externalshop/servlet/callback.php';
    }

}
