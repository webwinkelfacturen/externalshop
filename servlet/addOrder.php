<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Order;

$clientsecret = readValue('clientsecret');
$clientid     = readValue('clientid');

$order      = new Order($clientid, $clientsecret);
$response   = $order->add(createOrders());

print_r($response ); die;

function createOrders(){
    $orders = [
               'orderid'               => 119,
               'ordernumber'           => 'ORD005',
               'affiliatenr'           => 'testaff2',
               'paymentstatus'         => 'paid',
               'orderstatus'           => 'completed',
               'orderdate'             => '2021-03-01',
               'totalExclWithDiscount' => 100,
               'totalInclWithDiscount' => 121,
               'totalVatWithDiscount'  => 21,
               'totalDiscountIncl'     => 0,
               'totalDiscountExcl'     => 0,
               'items'                 => create_items(),
               'shipping'              => create_shipping(),
               'customer'              => create_customer(),
              ];
    print_r(json_encode(['order' => json_encode($orders)]));
    return json_encode(['order' => json_encode($orders)]);
}

function create_items() {
    $items = [
              [
               'referenceid'          => 119,
               'lineid'               => 1,
               'name'                 => 'productnaam2',
               'productcode'          => 'sku2',
               'quantity'             => 1,
               'lineInclWithDiscount' => 60.50,
               'lineExclWithDiscount' => 50,
               'lineVatWithDiscount'  => 10.50,
               'unitInclWithDiscount' => 60.50,
               'unitExclWithDiscount' => 50,
               'unitVatWithDiscount'  => 10.50,
               'lineDiscountIncl'     => 0,
               'lineDiscountExcl'     => 0,
               'lineDiscountVat'      => 0,
               'taxpercentage'        => 21,
              ]
             ];
    return $items;
}

function create_shipping() {
    $shipping = [
                 [
                  'referenceid'          => 119,
                  'shippingid'           => 1,
                  'name'                 => "Verzendkosten",
                  'lineInclWithDiscount' => 60.50,
                  'lineExclWithDiscount' => 50,
                  'lineVatWithDiscount'  => 10.50,
                  'taxpercentage'        => 21,
                 ]
                ];
    return $shipping;
}

function create_customer() {
    $customer = [
                 'customerid'          => 3,
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
                 'email'               => 'test3@gmail.com',
                ];
    return $customer;
}


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
