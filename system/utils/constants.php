<?php

namespace Externalshop\System\Utils;

class Constants {

    function getClientid(string $system = '') {
        return trim('gA68b9yyC3gWtjWqeHufzgsEEiFRpI3MzHwawVmZ');
    }

    function getClientsecret(string $system = '') {
        return trim('-JNPbVPd8qxRcppHgv6sNtinH');
    }

    function getEndpointurl(string $file = '') {
        return trim('https://wisteria.webwinkelfacturen.nl/' . $file);
    }

    function getWisteriaOAuthurl(string $system = '') {
        return trim('https://wisteria.webwinkelfacturen.nl/api');
    }

    function getCallbackurl(string $system = '') {
        return trim('https://secure136.cloudinvoice.company/api/v2/cloudinvoice/externalshop/servlet/callback.php');
    }

}
