<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Refund;

$authentication = new Authentication();
$clientsecret   = $authentication->readValue('clientsecret');
$clientid       = $authentication->readValue('clientid');

$refund      = new Refund($clientid, $clientsecret);
echo createRefunds();
$response     = $refund->add(createRefunds());

print_r($response ); die;

function createRefunds(){
    $refunds = [
                 'refundid'              => 5,
                 'refundnumber'          => 'REF005',
                 'customerid'            => 1,
                 'orderid'               => 3,
                 'ordernumber'           => 'ORD003',
                 'affiliatenr'           => 'testaff2',
                 'paymentdate'           => '2021-03-02',
                 'refunddate'            => '2021-03-01',
                 'totalinclwithdiscount' => 210,
                 'totalexclwithdiscount' => 233,
                 'totalvatwithdiscount'  => 32,
                 'lines'                 => create_lines(),
                 'customer'              => create_customer(),
                ];
    return json_encode(['refund' => json_encode($refunds)]);
}

function create_lines() {
    $lines = [
              [
               'refundid'      => 5,
               'refundlineid'  => 1,
               'name'          => 'productnaam2',
               'productcode'   => 'sku2',
               'quantity'      => 1,
               'linepriceincl' => 121,
               'linepriceexcl' => 100,
               'linepricevat'  => 21.00,
               'unitpriceincl' => 60.50,
               'unitpriceexcl' => 50,
               'unitpricevat'  => 10.50,
               'discountincl'  => 0,
               'discountexcl'  => 0,
               'discountvat'   => 0,
               'taxpercentage' => 21,
              ],
              [
               'refundid'     => 3,
               'refundlineid' => 2,
               'name'          => 'productnaam3',
               'productcode'   => 'sku3',
               'quantity'      => 2,
               'linepriceincl' => 109,
               'linepriceexcl' => 100,
               'linepricevat'  => 9.00,
               'unitpriceincl' => 54.50,
               'unitpriceexcl' => 50,
               'unitpricevat'  => 4.50,
               'discountincl'  => 0,
               'discountexcl'  => 0,
               'discountvat'   => 0,
               'taxpercentage' => 9,
              ]
             ];
    return $lines;
}

function create_customer() {
    $customer = [
                 'customerid'          => 1,
                 'customernumber'      => 'klantnummer',
                 'firstname'           => 'voornaam',
                 'lastname'            => 'achternaam',
                 'company'             => 'bedrijfsnaam',
                 'address1'            => 'adresregel1',
                 'address2'            => 'adresregel2',
                 'housenr'             => 'huisnr',
                 'zipcode'             => '1000 AA',
                 'city'                => 'Amsterdam',
                 'countryname'         => 'Nederland',
                 'isocountry'          => 'NL',
                 'mobile'              => '312309324342',
                 'email'               => 'test@gmail.com',
                ];
    return $customer;
}
