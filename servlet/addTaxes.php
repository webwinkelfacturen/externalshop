<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Tax;

$authentication = new Authentication();
$clientsecret   = $authentication->readValue('clientsecret');
$clientid       = $authentication->readValue('clientid');

$tax       = new Tax($clientid, $clientsecret);

$response  = $tax->add(createTaxes());

print_r($response ); die;

function createTaxes(){
    $taxes = [
              'taxid' => 12,
              'taxclasss' => 'standard',
              'taxcategory' => 'taxcat',
              'percentage' => 0.21,
              'percentage_100' => 21,
              'title' => 'testtax',
              'isdefault' => 0,
              'country' => 'BE',
              'type' => 'soort',
              'typename' => 'soortname'
             ];
    return json_encode(['tax' => json_encode($taxes)]);
}
