<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authorization;
use Externalshop\Processor\Tax;

$licensekey = '';
$taxid      = '';
if (!empty($argv) && count($argv) > 1) {
    $taxid      = $argv[1];
}

if (!empty($taxid)) {
    $authentication = new Authentication();
    $clientsecret   = $authentication->readValue('clientsecret');
    $clientid       = $authentication->readValue('clientid');

    $tax      = new Tax($clientid, $clientsecret);
    $response = $tax->delete(json_encode( ['tax' => json_encode(['id' => $taxid]) ]));

    print_r($response ); die;
} else {
    echo 'not enough parameters';
}
