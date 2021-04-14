<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Category;
use Externalshop\System\Utils\ConfigFile;

$clientsecret = readValue('clientsecret');
$clientid     = readValue('clientid');

$signature    = md5( gmdate("Ydm").gmdate("dmY")."BXuRNeU7oYlR9rdJhscub9bT1".gmdate("dmY") );

$category = new Category($clientid, $clientsecret);
$response = $category->readCategories($signature);

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
