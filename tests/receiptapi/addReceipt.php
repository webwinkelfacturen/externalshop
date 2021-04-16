<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Receipt;
use Externalshop\System\Utils\ArrayUtils;

class addReceiptsTest extends \PHPUnit\Framework\TestCase {

   /**
     * @dataProvider dataProviderReceipt
     */
    public function testReadReceipts($parms) {
        $this->deleteReceipts();

        $processor = new Receipt($parms['clientid'], $parms['clientsecret']);
       
        $result    = $processor->add($this->receipt1());
        //print_r($result);
        //die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderReceipt() {
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

    private function deleteReceipts() {
        $authentication = new Authentication();
        $processor      = new Receipt($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $receiptarray   = $processor->readReceipts($startdate, $enddate);
        $receipts       = [];
        if (is_array($receiptarray) && array_key_exists('data', $receiptarray)) {
            $receipts = $receiptarray['data'];
        }

        foreach ($receipts as $receipt) {
            $processor->delete($receipt['receiptid']);
        }
    }

    private function readResponse1() {
        return '{"data":{"receiptid":"112","receiptnumber":"REC005","receiptdate":"2021-03-21 00:00:00","customerid":"2","deliveryaddressid":null,"affiliatenr":"testaff2","receiptstatus":"completed","paymentstatus":"paid","totalInclWithDiscount":"109.0000","totalExclWithDiscount":"100.0000","totalVatWithDiscount":"9.0000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"isicp":null,"currency":null,"isinternational":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null,"lines":[],"customer":false,"fees":[],"payment":[]},"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function receipts():array {
        return [$this->receipt1(), $this->receipt2()];
    }

    private function receipt1():array {
        return [
                'receiptid'             => 112,
                'receiptnumber'         => 'REC005',
                'affiliatenr'           => 'testaff2',
                'paymentstatus'         => 'paid',
                'receiptstatus'         => 'completed',
                'receiptdate'           => '2021-03-21',
                'totalExclWithDiscount' => 100,
                'totalInclWithDiscount' => 109,
                'totalVatWithDiscount'  => 9,
                'customerid'            => 2,
                'lines'  => [
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
                              'totalDiscountIncl' => 0.0000,
                              'totalDiscountExcl' => 0.0000,
                             ]],
                'shipping' => [[
                               'referenceid'          => 112,
                               'lineid'               => 1,
                               'name'                 => 'Bag of Bananas',
                               'productcode'          => 'SKU_BOB',
                               'quantity'             => 1,
                               'lineInclWithDiscount' => 54.50,
                               'lineExclWithDiscount' => 50.00,
                               'lineVatWithDiscount'  => 4.50,
                               'taxpercentage'        => 9,
                             ]]
                            
               ];

    }

}
