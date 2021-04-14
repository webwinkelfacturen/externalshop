<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\PaymentMethod;

$licensekey = '';
$taxid      = '';
if (!empty($argv) && count($argv) > 1) {
    $pmid   = $argv[1];
}

if (!empty($pmid)) {
    $authentication = new Authentication();
    $clientsecret   = $authentication->readValue('clientsecret');
    $clientid       = $authentication->readValue('clientid');

    $pmethod  = new PaymentMethod($clientid, $clientsecret);
    $response = $pmethod->delete($pmid);

    print_r($response ); die;
} else {
    echo 'not enough parameters';
}
