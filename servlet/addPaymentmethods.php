<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Paymentmethod;

$authentication = new Authentication();
$clientsecret   = $authentication->readValue('clientsecret');
$clientid       = $authentication->readValue('clientid');

$paymentmethod      = new Paymentmethod($clientid, $clientsecret);
$response           = $paymentmethod->add(createPaymentmethods());

print_r($response ); die;

function createPaymentmethods(){
    $paymentmethods = [
                       'paymentmethodid' => 2,
                       'name'            => 'namepm2',
                       'type'            => 'standard',
                      ];
    return $paymentmethods;
}
