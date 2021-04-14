<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Order;

$authentication = new Authentication();
$clientsecret   = $authentication->readValue('clientsecret');
$clientid       = $authentication->readValue('clientid');

$startdate = '2021-03-01';
$enddate   = '2021-03-06';

$order    = new Order($clientid, $clientsecret);
$response = $order->readOrders($startdate, $enddate);

print_r($response ); die;
