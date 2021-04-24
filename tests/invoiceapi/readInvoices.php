<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Invoice;
use Externalshop\System\Utils\ArrayUtils;

class readInvoicesTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
    }

    public function tearDown() {
    }

   /**
     * @dataProvider dataProviderInvoice
     */
    public function testReadInvoices($parms) {
        //$this->deleteAllInvoices();
        $this->deleteInvoices();

        $this->addInvoices();
        $processor = new Invoice($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->readInvoices($parms['startdate'], $parms['enddate']);

        //print_r($result);
        //die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));

        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);


    }

	
    public function dataProviderInvoice() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['startdate']    = '2020-01-01';
        $parms1['enddate']      = date('Y-m-d');
        $parms1['enddate']      = '2021-05-01';
        $parms1['response']     = $this->readResponse1()['data'];
        return [
	    [$parms1],
	];
    }

    private function addInvoices() {
        $authentication = new Authentication();
        $processor      = new Invoice($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->invoice1());
        $processor->add($this->invoice2());
    }

    private function deleteAllInvoices() {
        $authentication   = new Authentication();
        $processor        = new Invoice($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));

        $startdate        = '2021-03-01';
        $enddate          = date('Y-m-d');

        $response        = $processor->deleteAll($startdate, $enddate);

        return $response;

    }

    private function deleteInvoices() {
        $authentication   = new Authentication();
        $processor        = new Invoice($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate        = '2020-01-01';
        $enddate          = date('Y-m-d');
        $invoiceresponse  = $processor->readInvoices($startdate, $enddate);
        $invoicearray     = $invoiceresponse;
        $invoices         = [];
        if (is_array($invoicearray) && array_key_exists('data', $invoicearray)) {
            $invoices = $invoicearray['data'];
        }

        foreach ($invoices as $invoice) {
            $processor->delete($invoice['invoiceid']);
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

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'createddate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function invoice1() {
        return [
                'invoiceid'             => 6,
                'orderid'               => 7,
                'invoicenumber'         => 'INV007',
                'affiliatenr'           => 'testaff2',
                'paymentdate'           => '2021-03-02',
                'invoicedate'           => '2021-03-21',
                'totalExclWithDiscount' => 200,
                'totalInclWithDiscount' => 230,
                'totalVatWithDiscount'  => 30,
                'lines' => [
                            [
                             'referenceid'          => 3,
                             'lineid'               => 1,
                             'name'                 => 'productnaam2',
                             'productcode'          => 'sku2',
                             'quantity'             => 2,
                             'lineInclWithDiscount' => 121,
                             'lineExclWithDiscount' => 100,
                             'lineVatWithDiscount'  => 21,
                             'unitInclWithDiscount' => 60.5,
                             'unitExclWithDiscount' => 50,
                             'unitVatWithDiscount'  => 10.5,
                             'taxpercentage'        => 21
                            ],
                            [
                             'referenceid'          => 3,
                             'lineid'               => 2,
                             'name'                 => 'productnaam3',
                             'productcode'          => 'sku3',
                             'quantity'             => 2,
                             'lineInclWithDiscount' => 109,
                             'lineExclWithDiscount' => 100,
                             'lineVatWithDiscount'  => 9,
                             'unitInclWithDiscount' => 54.5,
                             'unitExclWithDiscount' => 50,
                             'unitVatWithDiscount'  => 4.5,
                             'taxpercentage'        => 9
                            ]
                           ],
                'customer' => [
                               'customerid'     => 2,
                               'customernumber' => 'CUST_002',
                               'firstname'      => 'Jimmy',
                               'lastname'       => 'Doe',
                               'company'        => 'Sportschool',
                               'address1'       => 'Stationstraat',
                               'housenr'        => '12a',
                               'zipcode'        => '1000 AA',
                               'city'           => 'Amsterdam',
                               'countryname'    => 'Nederland',
                               'isocountry'     => 'NL',
                               'mobile'         => '312309324342',
                               'email'          => 'jimmy@mycompany.nl'
                              ]
                
               ];
    }

    private function invoice2() {
        return [
                'invoiceid'             => 90,
                'orderid'               => 80,
                'invoicenumber'         => 'INV009',
                'affiliatenr'           => 'testaff2',
                'paymentdate'           => '2021-03-02',
                'invoicedate'           => '2021-03-21',
                'totalExclWithDiscount' => 200,
                'totalInclWithDiscount' => 230,
                'totalVatWithDiscount'  => 30,
                'lines' => [
                            [
                             'referenceid'          => 3,
                             'lineid'               => 1,
                             'name'                 => 'productnaam2',
                             'productcode'          => 'sku2',
                             'quantity'             => 2,
                             'lineInclWithDiscount' => 121,
                             'lineExclWithDiscount' => 100,
                             'lineVatWithDiscount'  => 21,
                             'unitInclWithDiscount' => 60.5,
                             'unitExclWithDiscount' => 50,
                             'unitVatWithDiscount'  => 10.5,
                             'taxpercentage'        => 21
                            ],
                            [
                             'referenceid'          => 3,
                             'lineid'               => 2,
                             'name'                 => 'productnaam3',
                             'productcode'          => 'sku3',
                             'quantity'             => 2,
                             'lineInclWithDiscount' => 109,
                             'lineExclWithDiscount' => 100,
                             'lineVatWithDiscount'  => 9,
                             'unitInclWithDiscount' => 54.5,
                             'unitExclWithDiscount' => 50,
                             'unitVatWithDiscount'  => 4.5,
                             'taxpercentage'        => 9
                            ]
                           ],
                'customer' => [
                               'customerid'     => 2,
                               'customernumber' => 'CUST_002',
                               'firstname'      => 'Jimmy',
                               'lastname'       => 'Doe',
                               'company'        => 'Sportschool',
                               'address1'       => 'Stationstraat',
                               'housenr'        => '12a',
                               'zipcode'        => '1000 AA',
                               'city'           => 'Amsterdam',
                               'countryname'    => 'Nederland',
                               'isocountry'     => 'NL',
                               'mobile'         => '312309324342',
                               'email'          => 'jimmy@mycompany.nl'
                              ]
                
               ];
    }

    private function readResponse1() {
        return json_decode('{"data":[{"id":"39","licensekey":"3831223f2dbe887281b6e1698d23c6","invoiceid":"6","invoicenumber":"INV007","isicp":null,"currency":null,"isinternational":null,"invoicestatus":null,"paymentstatus":null,"invoicedate":"2021-03-21 00:00:00","affiliatenr":"testaff2","cartnr":null,"customerid":"2","orderid":"7","iscredit":null,"duedays":null,"duedate":null,"pdf":null,"totalpaid":null,"totalunpaid":null,"totalInclWithDiscount":"230.0000","totalExclWithDiscount":"200.0000","totalVatWithDiscount":"30.0000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"createddate":"2021-04-15 00:00:00","changedate":null},{"id":"40","licensekey":"3831223f2dbe887281b6e1698d23c6","invoiceid":"90","invoicenumber":"INV009","isicp":null,"currency":null,"isinternational":null,"invoicestatus":null,"paymentstatus":null,"invoicedate":"2021-03-21 00:00:00","affiliatenr":"testaff2","cartnr":null,"customerid":"2","orderid":"80","iscredit":null,"duedays":null,"duedate":null,"pdf":null,"totalpaid":null,"totalunpaid":null,"totalInclWithDiscount":"230.0000","totalExclWithDiscount":"200.0000","totalVatWithDiscount":"30.0000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"createddate":"2021-04-15 00:00:00","changedate":null}],"message":"Result"}', true);
    }
}
