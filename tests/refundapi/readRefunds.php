<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Refund;
use Externalshop\System\Utils\ArrayUtils;

class readRefunds extends \PHPUnit\Framework\TestCase {

    public function setUp(): void
    {
        $this->addRefunds();
    }

   /**
     * @dataProvider dataProviderRefund
     */
    public function testReadRefunds(array $parms): void
    {
        $processor = new Refund($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->readRefunds($parms['startdate'], $parms['enddate']);
        //print_r(json_encode($result['data'])); die();
        //print_r($result); die();
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

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

    private function addRefunds() {
        $this->deleteRefunds();
        $authentication = new Authentication();
        $processor      = new Refund($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->refunds1());
        $processor->add($this->refunds2());
    }

    private function deleteRefunds() {
        $authentication = new Authentication();
        $processor      = new Refund($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $refundsresponse  = $processor->readRefunds($startdate, $enddate);
        $refundsarray     = /*json_decode(*/$refundsresponse/*, true)*/;
        $refundss         = [];
        if (is_array($refundsarray) && array_key_exists('data', $refundsarray)) {
            $refundss = $refundsarray['data'];
        }

        foreach ($refundss as $refunds) {
            $processor->delete($refunds['refundid']);
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
        return '{"data":[{"refundid":"122","refundnumber":"RET004","orderid":"112","customerid":"","ordernumber":"ORD003","affiliatenr":"testaff2","refundstatus":"completed","refunddate":"2021-03-21 00:00:00","paymentstatus":"paid","totalInclWithDiscount":"-163.5000","totalExclWithDiscount":"-150.0000","totalVatWithDiscount":"-13.5000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"isicp":null,"isinternational":null,"currency":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null},{"refundid":"123","refundnumber":"RET001","orderid":"112","customerid":"","ordernumber":"ORD003","affiliatenr":"testaff2","refundstatus":"completed","refunddate":"2021-03-21 00:00:00","paymentstatus":"paid","totalInclWithDiscount":"-163.5000","totalExclWithDiscount":"-150.0000","totalVatWithDiscount":"-13.5000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"isicp":null,"isinternational":null,"currency":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null}],"message":"Result"}';
    }

    
    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function refunds1() {
      return [
                'refundid'              => 122,
                'refundnumber'          => 'RET004',
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
                               'customerid' => 5,
                               'customernumber' => 'CUST001',
                               'firstname'      => 'Jimmy',
                               'lastname'       => 'Doe',
                               'company'        => 'Grocery online',
                               'address1'       => 'Stationstraat 12',
                               'zipcode'        => '1000 AA',
                               'city'           => 'Amsterdam',
                               'isocountry'     => 'NL',
                               'mobile'         => '0612345678',
                               'email'          => 'jimmy1504@mycompany.nl',
                              ]
               ];

    }
    private function refunds2() {

        return [
                'refundid'              => 123,
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
                               'customerid' => 6,
                               'customernumber' => 'CUST001',
                               'firstname'      => 'Jean',
                               'lastname'       => 'Doe',
                               'company'        => 'Grocery online',
                               'address1'       => 'Stationstraat 12',
                               'zipcode'        => '1000 AA',
                               'city'           => 'Amsterdam',
                               'isocountry'     => 'NL',
                               'mobile'         => '0612345678',
                               'email'          => 'jean1504@mycompany.nl',
                              ]
               ];
    }
}
