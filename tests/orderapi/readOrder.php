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

    public function tearDown() {
        $this->deleteOrders();
    }

   /**
     * @dataProvider dataProviderOrder
     */
    public function testReadOrders($parms) {
        $processor = new Order($parms['clientid'], $parms['clientsecret']);
        $result    = json_decode($processor->readOrders($parms['startdate'], $parms['enddate']), true);
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
        $res1 = $processor->add($this->order1());
        $res2 = $processor->add($this->order2());
    }

    private function deleteOrders() {
        $authentication = new Authentication();
        $processor      = new Order($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $orderresponse  = $processor->readOrders($startdate, $enddate);
        $orderarray     = json_decode($orderresponse, true);
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
        $customerresponse = $processor->readCustomers();
        $customerarray    = json_decode($customerresponse, true);
        $customers        = [];
        if (is_array($customerarray) && array_key_exists('data', $customerarray)) {
            $customers = $customerarray['data'];
        }

        foreach ($customers as $customer) {
            $processor->delete($customer['customerid']);
        }

    }

    private function readResponse1() {
        return '{"data":[{"id":"5","licensekey":"c4365634cedbe359e75020b9ae8b26","orderid":"112","affiliatenr":"testaff2","cartnr":null,"customerid":"1","paymentstatus":"paid","orderstatus":"completed","ordernumber":"ORD003","orderdate":"2021-03-01 00:00:00","deliverydate":null,"totalExclWithDiscount":"100.0000","totalInclWithDiscount":"121.0000","totalVatWithDiscount":"21.0000","totalDiscountIncl":"0.0000","totalDiscountExcl":"0.0000","changedate":null,"creationdate":"2021-03-08 00:00:00"},{"id":"6","licensekey":"c4365634cedbe359e75020b9ae8b26","orderid":"118","affiliatenr":"testaff2","cartnr":null,"customerid":"2","paymentstatus":"paid","orderstatus":"completed","ordernumber":"ORD004","orderdate":"2021-03-01 00:00:00","deliverydate":null,"totalExclWithDiscount":"100.0000","totalInclWithDiscount":"121.0000","totalVatWithDiscount":"21.0000","totalDiscountIncl":"0.0000","totalDiscountExcl":"0.0000","changedate":null,"creationdate":"2021-03-08 00:00:00"}],"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function order1() {
        return '{"order":"{\"orderid\":112,\"ordernumber\":\"ORD003\",\"affiliatenr\":\"testaff2\",\"paymentstatus\":\"paid\",\"orderstatus\":\"completed\",\"orderdate\":\"2021-03-01\",\"totalExclWithDiscount\":100,\"totalInclWithDiscount\":121,\"totalVatWithDiscount\":21,\"totalDiscountIncl\":0,\"totalDiscountExcl\":0,\"items\":[{\"orderid\":2,\"lineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincltax\":60.5,\"linepriceexcltax\":50,\"linetax\":10.5,\"unitpriceincltax\":60.5,\"unitpriceexcltax\":50,\"unittax\":10.5,\"discountincltax\":0,\"discountexcltax\":0,\"discounttax\":0,\"taxpercentage\":21}],\"shipping\":[{\"orderid\":2,\"shippingid\":1,\"name\":\"Verzendkosten\",\"priceincltax\":60.5,\"priceexcltax\":50,\"tax\":10.5,\"taxpercentage\":21}],\"customer\":{\"customerid\":1,\"customernumber\":\"klantnummer\",\"firstname\":\"voornaam\",\"lastname\":\"achternaam\",\"company\":\"bedrijfsnaam\",\"address1\":\"adresregel1\",\"address2\":\"adresregel2\",\"housenr\":\"huisnr\",\"zipcode\":\"1000 AA\",\"city\":\"Amsterdam\",\"countryname\":\"Nederland\",\"isocountry\":\"NL\",\"mobile\":\"312309324342\",\"email\":\"test@gmail.com\"}}"}';
    }

    private function order2() {
       return '{"order":"{\"orderid\":118,\"ordernumber\":\"ORD004\",\"affiliatenr\":\"testaff2\",\"paymentstatus\":\"paid\",\"orderstatus\":\"completed\",\"orderdate\":\"2021-03-01\",\"totalExclWithDiscount\":100,\"totalInclWithDiscount\":121,\"totalVatWithDiscount\":21,\"totalDiscountIncl\":0,\"totalDiscountExcl\":0,\"items\":[{\"orderid\":2,\"lineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincltax\":60.5,\"linepriceexcltax\":50,\"linetax\":10.5,\"unitpriceincltax\":60.5,\"unitpriceexcltax\":50,\"unittax\":10.5,\"discountincltax\":0,\"discountexcltax\":0,\"discounttax\":0,\"taxpercentage\":21}],\"shipping\":[{\"orderid\":2,\"shippingid\":1,\"name\":\"Verzendkosten\",\"priceincltax\":60.5,\"priceexcltax\":50,\"tax\":10.5,\"taxpercentage\":21}],\"customer\":{\"customerid\":2,\"customernumber\":\"klantnummer\",\"firstname\":\"voornaam\",\"lastname\":\"achternaam\",\"company\":\"bedrijfsnaam\",\"address1\":\"adresregel1\",\"address2\":\"adresregel2\",\"housenr\":\"huisnr\",\"zipcode\":\"1000 AA\",\"city\":\"Amsterdam\",\"countryname\":\"Nederland\",\"isocountry\":\"NL\",\"mobile\":\"312309324342\",\"email\":\"test2@gmail.com\"}}"}';
    }
}
