<?php

namespace Externalshop\System\Utils;

class Constants {

    function getClientid(string $system = '') {
        return trim('znGCiTEYhDR.H7xobQ9mLgx4s-oZwIgH2bnLzJT5');
        return trim('gA68b9yyC3gWtjWqeHufzgsEEiFRpI3MzHwawVmZ');
    }

    function getClientsecret(string $system = '') {
        return trim('DBsrN.lt6RZ6G4TJGtdGbDo.F');
        return trim('-JNPbVPd8qxRcppHgv6sNtinH');
    }

    function getEndpointurl(string $file = '') {
        return 'https://docker1.winkelboekhouding.nl/'.$file;
        return trim('https://wisteria.webwinkelfacturen.nl/' . $file);
    }

    function getWisteriaOAuthurl(string $system = '') {
        return 'https://docker1.winkelboekhouding.nl/api';
        return trim('https://wisteria.webwinkelfacturen.nl/api');
    }

    function getCallbackurl(string $system = '') {
        return trim('https://secure136.cloudinvoice.company/api/v2/cloudinvoice/externalshop/servlet/callback.php');
    }

}
