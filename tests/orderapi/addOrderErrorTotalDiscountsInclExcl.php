<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Order;
use Externalshop\System\Utils\ArrayUtils;

class addOrderErrorTotalDiscountsInclExcl extends \PHPUnit\Framework\TestCase {

    public function setUp(): void
    {
        $this->deleteOrders();
    }

    public function tearDown(): void
    {
        $this->deleteOrders();
    }

   /**
     * @dataProvider dataProviderOrder
     */
    public function testAddOrderNoSyncDiscountsInclExl(array $parms): void
    {
        $processor = new Order($parms['clientid'], $parms['clientsecret']);
        $result    = $this->addOrders();
        //print_r($result);
        //die();

        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('error', $result));
        $this->assertTrue(array_key_exists('errorcode', $result));
        $this->assertTrue($result['error']         == 422);
        $this->assertTrue($result['errorcode']     == 'ORD102_TOTALDISCOUNTS_NOMATCH');
        $this->assertTrue($result['errormessage']  == 'The sum of the ordertotaldiscounts excl, incl and vat do not match');
        $this->assertTrue(strpos($result['specification'], 'Fields concerned are totalDiscountIncl, totalDiscountExcl, totalDiscountVat') !== false);
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
        $authentication = new Authentication();
        $processor      = new Order($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        return $processor->add($this->order1());
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

        foreach ($orders as $order) {
            $processor->delete($order['orderid']);
        }
    }

    private function readResponse1() {
        return '';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function order1() {
        return '{"order":"{\"orderid\":112,\"ordernumber\":\"ORD003\",\"affiliatenr\":\"testaff2\",\"customerid\":\"1\",\"paymentstatus\":\"paid\",\"orderstatus\":\"completed\",\"orderdate\":\"2021-03-01\",\"totalExclWithDiscount\":100,\"totalInclWithDiscount\":121,\"totalVatWithDiscount\":21,\"totalDiscountIncl\":2,\"totalDiscountExcl\":0,\"items\":[{\"orderid\":2,\"lineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincltax\":60.5,\"linepriceexcltax\":50,\"linetax\":10.5,\"unitpriceincltax\":60.5,\"unitpriceexcltax\":50,\"unittax\":10.5,\"discountincltax\":0,\"discountexcltax\":0,\"discounttax\":0,\"taxpercentage\":21}],\"shipping\":[{\"orderid\":2,\"shippingid\":1,\"name\":\"Verzendkosten\",\"priceincltax\":60.5,\"priceexcltax\":50,\"tax\":10.5,\"taxpercentage\":21}]}"}';
    }

}
