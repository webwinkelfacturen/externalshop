<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Order;
use Externalshop\System\Utils\ArrayUtils;

class addOrdersTest extends \PHPUnit\Framework\TestCase {

   /**
     * @dataProvider dataProviderOrder
     */
    public function testReadOrders($parms) {
        $this->deleteOrders();

        $processor = new Order($parms['clientid'], $parms['clientsecret']);
        //print_r($this->order1()); die();
        $result    = $processor->add($this->order1());
        //print_r($result);
        //die();
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 1);

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

    private function deleteOrders() {
        $authentication = new Authentication();
        $processor      = new Order($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $orderarray     = $processor->readOrders($startdate, $enddate);
        $orders         = [];
        if (is_array($orderarray) && array_key_exists('data', $orderarray)) {
            $orders = $orderarray['data'];
        }

        //print_r($orderarray); die();
        foreach ($orders as $order) {
            $processor->delete($order['orderid']);
        }

        $this->deleteCustomers();
    }

    private function deleteCustomers() {
        $authentication   = new Authentication();
        $processor        = new Customer($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $customerarray    = $processor->readCustomers();
        //$customerarray    = json_decode($customerresponse, true);
        $customers        = [];
        if (is_array($customerarray) && array_key_exists('data', $customerarray)) {
            $customers = $customerarray['data'];
        }

        foreach ($customers as $customer) {
            $processor->delete($customer['customerid']);
        }

    }

    private function readResponse1() {
        return '{"data":[{"orderid":"112","affiliatenr":"testaff2","cartnr":null,"currency":null,"customerid":"1","deliveryaddressid":null,"paymentstatus":"paid","orderstatus":"completed","ordernumber":"ORD003","orderdate":"2021-03-21 00:00:00","deliverydate":null,"totalDiscountVat":null,"totalDiscountIncl":null,"totalDiscountExcl":null,"totalInclWithDiscount":"109.0000","totalExclWithDiscount":"100.0000","totalVatWithDiscount":"9.0000","items":[],"customer":{"customerid":"1","orderid":"112","customernumber":"CUST001","firstname":"Jean","lastname":"Doe","company":"Grocery online","address1":"Stationstraat 12","address2":null,"housenr":null,"zipcode":"1000 AA","city":"Amsterdam","state":null,"country":null,"isocountry":"NL","kvk":null,"btwnr":null,"telnr":null,"mobile":"0612345678","email":"jean@mycompany.nl","iscompany":null,"isicp":null,"isinternational":null,"incltax":null},"shipping":[{"name":"Verzendkosten","referenceid":"112","shippingid":"1","code":null,"taxpercentage":"9.0000","unitInclWithDiscount":null,"unitExclWithDiscount":null,"unitVatWithDiscount":null,"unitDiscountIncl":null,"unitDiscountExcl":null,"unitDiscountVat":null,"createddate":"2021-04-13 00:00:00"}],"payment":[],"fees":[]}],"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'createddate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function orders():array {
        return [$this->order1(), $this->order2()];
    }

    private function order1():array {
        return [
                'orderid' => 112,
                'ordernumber' => 'ORD003',
                'affiliatenr' => 'testaff2',
                'paymentstatus' => 'paid',
                'orderstatus' => 'completed',
                'orderdate' => '2021-03-21',
                'totalExclWithDiscount' => 100,
                'totalInclWithDiscount' => 109,
                'totalVatWithDiscount'  => 9,
                'items'  => [
                             [
                              'referenceid' => 112,
                              'lineid'  => 1,
                              'name'    => 'Bag of Apples',
                              'productcode' => 'SKU_BOA',
                              'quantity' => 1,
                              'lineInclWithDiscount' => 54.50,
                              'lineExclWithDiscount' => 50.00,
                              'lineVatWithDiscount'  => 4.50,
                              'taxpercentage'  => 9,
                             ]
                            ],
                'shipping' => [[
                               'referenceid' => 112,
                               'shippingid' => 1,
                               'name' => 'Verzendkosten',
                               'quantity' => 1,
                               'lineInclWithDiscount' => 54.50,
                               'lineExclWithDiscount' => 50.00,
                               'lineVatWithDiscount'  => 4.50,
                               'taxpercentage'  => 9,
                              ]],
                'customer' => [
                               'customerid' => 1,
                               'customernumber' => 'CUST001',
                               'firstname'      => 'Jean',
                               'lastname'       => 'Doe',
                               'company'        => 'Grocery online',
                               'address1'       => 'Stationstraat 12',
                               'zipcode'        => '1000 AA',
                               'city'           => 'Amsterdam',
                               'isocountry'     => 'NL',
                               'mobile'         => '0612345678',
                               'email'          => 'jean@mycompany.nl',
                              ]
               ];

    }

    private function order2():array {
        return [
                'orderid' => 113,
                'ordernumber' => 'ORD004',
                'affiliatenr' => 'testaff4',
                'paymentstatus' => 'paid',
                'orderstatus' => 'completed',
                'orderdate' => '2021-03-24',
                'totalExclWithDiscount' => 100,
                'totalInclWithDiscount' => 109,
                'totalVatWithDiscount'  => 9,
                'items'  => [
                             [
                              'orderid' => 112,
                              'lineid'  => 1,
                              'name'    => 'Bag of Apples',
                              'productcode' => 'SKU_BOA',
                              'quantity' => 1,
                              'lineInclWithDiscount' => 54.50,
                              'lineExclWithDiscount' => 50.00,
                              'lineVatWithDiscount'  => 4.50,
                              'taxpercentage'  => 9,
                             ],
                             ['orderid' => 112,
                              'lineid' => 1,
                              'name' => 'Bag of Bananas',
                              'productcode' => 'SKU_BOB',
                              'quantity' => 3,
                              'lineInclWithDiscount' => 54.50,
                              'lineExclWithDiscount' => 50.00,
                              'lineVatWithDiscount'  => 4.50,
                              'taxpercentage'  => 9,
                             ],
                            ],
                'customer' => [
                               'customerid' => 2,
                               'customernumber' => 'CUST002',
                               'firstname'      => 'James',
                               'lastname'       => 'Doe',
                               'company'        => 'Bees online',
                               'address1'       => 'Stationstraat 12',
                               'zipcode'        => '1000 AA',
                               'city'           => 'Amsterdam',
                               'isocountry'     => 'NL',
                               'mobile'         => '0612345678',
                               'email'          => 'james@mycompany.nl',
                              ]
               ];

    }

}
