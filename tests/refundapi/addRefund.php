<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Refund;
use Externalshop\System\Utils\ArrayUtils;

class addRefundTest extends \PHPUnit\Framework\TestCase {

   /**
     * @dataProvider dataProviderRefund
     */
    public function testReadRefunds($parms) {
        $this->deleteRefunds();

        $processor = new Refund($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->add($this->refund1());
        //print_r($result);
        //die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderRefund() {
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

    private function deleteRefunds() {
        $authentication = new Authentication();
        $processor      = new Refund($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2020-01-01';
        $enddate        = date('Y-m-d');
        $refundarray     = $processor->readRefunds($startdate, $enddate);
	
        $refunds         = [];
        if (is_array($refundarray) && array_key_exists('data', $refundarray)) {
            $refunds = $refundarray['data'];
        }

        foreach ($refunds as $refund) {
            $processor->delete($refund['refundid']);
        }

        $this->deleteCustomers();
    }

    private function deleteCustomers() {
        $authentication = new Authentication();
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
        return '{"data":{"refundid":"122","refundnumber":"RET001","orderid":"112","customerid":"","ordernumber":"ORD003","affiliatenr":"testaff2","refundstatus":"completed","refunddate":"2021-03-21 00:00:00","paymentstatus":"paid","totalInclWithDiscount":"-163.5000","totalExclWithDiscount":"-150.0000","totalVatWithDiscount":"-13.5000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"isicp":null,"isinternational":null,"currency":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null,"items":[],"customer":false,"fees":[],"shipping":[],"payment":[]},"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function refunds():array {
        return [$this->refund1()];
    }

    private function refund1():array {
        return [
                'refundid'              => 122,
                'refundnumber'          => 'RET001',
                'orderid'               => 112,
                'ordernumber'           => 'ORD003',
                'affiliatenr'           => 'testaff2',
                'paymentstatus'         => 'paid',
                'refundstatus'          => 'completed',
                'refunddate'            => '2021-03-21',
                'totalExclWithDiscount' => -150,
                'totalInclWithDiscount' => -163.50,
                'totalVatWithDiscount'  => -13.50,
                'lines'  => [
                             [
                              'referenceid'          => 112,
                              'lineid'               => 1,
                              'name'                 => 'Bag of Apples',
                              'productcode'          => 'SKU_BOA',
                              'quantity'             => -2,
                              'lineInclWithDiscount' => -109.00,
                              'lineExclWithDiscount' => -100.00,
                              'lineVatWithDiscount'  => -9.00,
                              'unitInclWithDiscount' => 54.50,
                              'unitExclWithDiscount' => 50.00,
                              'unitVatWithDiscount'  => 4.50,
                              'taxpercentage'        => 9,
                             ]
                            ],
                'shipping' => [
                               [
                                'referenceid'          => 112,
                                'shippingid'           => 1,
                                'name'                 => 'Verzendkosten',
                                'quantity'             => -1,
                                'lineInclWithDiscount' => -54.50,
                                'lineExclWithDiscount' => -50.00,
                                'lineVatWithDiscount'  => -4.50,
                                'unitInclWithDiscount' => 54.50,
                                'unitExclWithDiscount' => 50.00,
                                'unitVatWithDiscount'  => 4.50,
                                'taxpercentage'        => 9,
                               ]
                              ],
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

}
