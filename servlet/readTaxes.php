<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Tax;

$clientsecret = readValue('clientsecret');
$clientid     = readValue('clientid');

$signature    = md5( gmdate("Ydm").gmdate("dmY")."BXuRNeU7oYlR9rdJhscub9bT1".gmdate("dmY") );

$tax      = new Tax($clientid, $clientsecret);
$response = $tax->readTaxes($signature);

print_r( $response ); die;
print_r( json_decode($response, true) ); die;

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
