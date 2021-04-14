<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Invoice;

$authentication = new Authentication();
$clientsecret   = $authentication->readValue('clientsecret');
$clientid       = $authentication->readValue('clientid');

$invoice      = new Invoice($clientid, $clientsecret);
$response     = $invoice->add(createInvoices());

print_r($response ); die;

function createInvoices(){
    $invoices = [
                 'invoiceid'     => 4,
                 'orderid'       => 4,
                 'invoicenumber' => 'INV004',
                 'affiliatenr'   => 'testaff2',
                 'paymentdate'   => '2021-03-02',
                 'invoicedate'   => '2021-03-01',
                 'invoicediscountExcl' => 0,
                 'invoicediscountIncl' => 0,
                 'totalExcl'     => 210,
                 'totalIncl'     => 233,
                 'totalVat'      => 32,
                 'lines'         => create_lines(),
                 'customer'      => create_customer(),
                ];
    return json_encode(['invoice' => json_encode($invoices)]);
}

function create_lines() {
    $lines = [
              [
               'invoiceid'     => 3,
               'invoicelineid' => 1,
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
               'invoiceid'     => 3,
               'invoicelineid' => 2,
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
