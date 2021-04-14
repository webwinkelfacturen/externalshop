<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Order;
use Externalshop\System\Utils\ArrayUtils;

class readOrdersTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addOrders();
    }

   /**
     * @dataProvider dataProviderOrder
     */
    public function testReadOrders($parms) {
        $processor = new Order($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->readOrders($parms['startdate'], $parms['enddate']);
	//print_r($result); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderOrder() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['startdate']    = '2020-01-01';
        $parms1['enddate']      = date('Y-m-d');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
	];
    }

    private function addOrders() {
        $this->deleteOrders();
        $authentication = new Authentication();
        $processor      = new Order($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $res1 = $processor->add(json_decode($this->order1(),true));
        $res2 = $processor->add(json_decode($this->order2(),true));
    }

    private function deleteOrders() {
        $authentication = new Authentication();
        $processor      = new Order($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2020-01-01';
        $enddate        = date('Y-m-d');
        $orderarray     = $processor->readOrders($startdate, $enddate);
        $orders         = [];
        if (is_array($orderarray) && array_key_exists('data', $orderarray)) {
            $orders = $orderarray['data'];
        }

        foreach ($orders as $order) {
            $processor->delete($order['orderid']);
        }

        $this->deleteCustomers();
    }

    private function deleteCustomers() {
        $authentication = new Authentication();
        $processor        = new Customer($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $customerarray = $processor->readCustomers();
        $customers        = [];
        if (is_array($customerarray) && array_key_exists('data', $customerarray)) {
            $customers = $customerarray['data'];
        }

        foreach ($customers as $customer) {
            $processor->delete($customer['customerid']);
        }

    }

    private function readResponse1() {
        return '{"data":[{"id":"33","licensekey":"3831223f2dbe887281b6e1698d23c6","orderid":"112","affiliatenr":"testaff2","cartnr":null,"currency":null,"customerid":"1","deliveryaddressid":null,"paymentstatus":"paid","orderstatus":"completed","ordernumber":"ORD003","orderdate":"2021-03-01 00:00:00","deliverydate":null,"totalDiscountVat":null,"totalDiscountIncl":"0.0000","totalDiscountExcl":"0.0000","totalInclWithDiscount":"121.0000","totalExclWithDiscount":"100.0000","totalVatWithDiscount":"21.0000","changedate":null,"createddate":"2021-04-13 00:00:00"},{"id":"34","licensekey":"3831223f2dbe887281b6e1698d23c6","orderid":"118","affiliatenr":"testaff2","cartnr":null,"currency":null,"customerid":"2","deliveryaddressid":null,"paymentstatus":"paid","orderstatus":"completed","ordernumber":"ORD004","orderdate":"2021-03-01 00:00:00","deliverydate":null,"totalDiscountVat":null,"totalDiscountIncl":"0.0000","totalDiscountExcl":"0.0000","totalInclWithDiscount":"121.0000","totalExclWithDiscount":"100.0000","totalVatWithDiscount":"21.0000","changedate":null,"createddate":"2021-04-13 00:00:00"}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'createddate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function order1() {
        return '{"orderid":112,"ordernumber":"ORD003","affiliatenr":"testaff2","paymentstatus":"paid","orderstatus":"completed","orderdate":"2021-03-01","totalExclWithDiscount":100,"totalInclWithDiscount":121,"totalVatWithDiscount":21,"totalDiscountIncl":0,"totalDiscountExcl":0,"items":[{"referenceid":112,"lineid":1,"name":"productnaam2","productcode":"sku2","quantity":1,"lineInclWithDiscount":60.5,"lineExclWithDiscount":50,"lineVatWithDiscount":10.5,"unitInclWithDiscount":60.5,"unitExclWithDiscount":50,"unitVatWithDiscount":10.5,"taxpercentage":21}],"shipping":[{"referenceid":112,"shippingid":1,"name":"Verzendkosten","lineInclWithDiscount":60.5,"lineExclWithDiscount":50,"lineVatWithDiscount":10.5,"taxpercentage":21}],"customer":{"customerid":1,"customernumber":"klantnummer","firstname":"voornaam","lastname":"achternaam","company":"bedrijfsnaam","address1":"adresregel1","address2":"adresregel2","housenr":"huisnr","zipcode":"1000 AA","city":"Amsterdam","countryname":"Nederland","isocountry":"NL","mobile":"312309324342","email":"test@gmail.com"}}';
    }

    private function order2() {
       return '{"orderid":118,"ordernumber":"ORD004","affiliatenr":"testaff2","paymentstatus":"paid","orderstatus":"completed","orderdate":"2021-03-01","totalExclWithDiscount":100,"totalInclWithDiscount":121,"totalVatWithDiscount":21,"totalDiscountIncl":0,"totalDiscountExcl":0,"items":[{"referenceid":118,"lineid":1,"name":"productnaam2","productcode":"sku2","quantity":1,"lineInclWithDiscount":60.5,"lineExclWithDiscount":50,"lineVatWithDiscount":10.5,"unitInclWithDiscount":60.5,"unitExclWithDiscount":50,"unitVatWithDiscount":10.5,"taxpercentage":21}],"shipping":[{"referenceid":118,"shippingid":1,"name":"Verzendkosten","lineInclWithDiscount":60.5,"lineExclWithDiscount":50,"lineVatWithDiscount":10.5,"taxpercentage":21}],"customer":{"customerid":2,"customernumber":"klantnummer","firstname":"voornaam","lastname":"achternaam","company":"bedrijfsnaam","address1":"adresregel1","address2":"adresregel2","housenr":"huisnr","zipcode":"1000 AA","city":"Amsterdam","countryname":"Nederland","isocountry":"NL","mobile":"312309324342","email":"test2@gmail.com"}}';
    }
}
