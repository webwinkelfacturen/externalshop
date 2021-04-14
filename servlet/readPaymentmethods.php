<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Paymentmethod;

$clientsecret = readValue('clientsecret');
$clientid     = readValue('clientid');

$paymentmethod = new Paymentmethod($clientid, $clientsecret);
$response      = $paymentmethod->readPaymentmethods();

print_r($response ); die;

function readValue(string $key):string {
    $oauthfile = dirname(__FILE__) . '/../../../../files/externalshop/oauth/user.cnf';
    $handle = fopen($oauthfile, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $arr = explode('=', $line);
            if ($arr[0] == $key) {
                return $arr[1];
            }
        }
        fclose($handle);
    }
    return '';
}
