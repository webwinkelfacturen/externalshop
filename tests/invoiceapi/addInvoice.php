<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Invoice;
use Externalshop\System\Utils\ArrayUtils;

class addInvoice extends \PHPUnit\Framework\TestCase {

    public function setUp(): void
    {
    }

    public function tearDown():void
    {
    }

   /**
     * @dataProvider dataProviderInvoice
     */
    public function testReadInvoices(array $parms): void
    {
        $this->deleteInvoices();
        $processor = new Invoice($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->add($this->invoice1());
	//print_r(count($result['data'])); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderInvoice() {
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

    private function deleteInvoices() {
        $authentication = new Authentication();
        $processor      = new Invoice($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $invoicearray   = $processor->readInvoices($startdate, $enddate);
        $invoices       = [];
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
        $processor      = new Customer($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $customerarray  = $processor->readCustomers();
        $customers      = [];
        if (is_array($customerarray) && array_key_exists('data', $customerarray)) {
            $customers = $customerarray['data'];
        }

        foreach ($customers as $customer) {
            $processor->delete($customer['customerid']);
        }

    }

    private function readResponse1() {
        return '{"data":{"invoiceid":"4","invoicenumber":"INV004","isicp":null,"currency":null,"isinternational":null,"invoicestatus":null,"paymentstatus":null,"invoicedate":"2021-03-01 00:00:00","affiliatenr":"testaff2","cartnr":null,"customerid":"2","orderid":"4","iscredit":null,"duedays":null,"duedate":null,"pdf":null,"totalpaid":null,"totalunpaid":null,"totalInclWithDiscount":"230.0000","totalExclWithDiscount":"200.0000","totalVatWithDiscount":"30.0000","totalDiscountIncl":null,"totalDiscountExcl":null,"totalDiscountVat":null,"lines":[],"customer":{"customerid":"2","orderid":"2","customernumber":"CUST_002","firstname":"Jimmy","lastname":"Doe","company":"Sportschool","address1":"Stationstraat","address2":null,"housenr":"12a","zipcode":"1000 AA","city":"Amsterdam","state":null,"country":null,"isocountry":"NL","kvk":null,"btwnr":null,"telnr":null,"mobile":"312309324342","email":"jimmy@mycompany.nl","iscompany":null,"isicp":null,"isinternational":null,"incltax":null},"fees":[],"payment":[],"shipping":[]},"message":"Your data is inserted successfully"}';
        return '{"data":[{"id":"4","licensekey":"c4365634cedbe359e75020b9ae8b26","invoiceid":"4","invoicenumber":"INV004","isicp":null,"isinternational":null,"paymentdate":"2021-03-02 00:00:00","invoicedate":"2021-03-01 00:00:00","affiliatenr":"testaff2","cartnr":null,"orderid":"4","iscredit":null,"duedays":null,"duedate":null,"pdf":null,"totalpaid":null,"totalunpaid":null,"invoicediscountExcl":"0.0000","invoicediscountIncl":"0.0000","creationdate":"2021-03-08 00:00:00","changedate":null},{"id":"4","licensekey":"c4365634cedbe359e75020b9ae8b26","invoiceid":"4","invoicenumber":"INV004","isicp":null,"isinternational":null,"paymentdate":"2021-03-02 00:00:00","invoicedate":"2021-03-01 00:00:00","affiliatenr":"testaff2","cartnr":null,"orderid":"4","iscredit":null,"duedays":null,"duedate":null,"pdf":null,"totalpaid":null,"totalunpaid":null,"invoicediscountExcl":"0.0000","invoicediscountIncl":"0.0000","creationdate":"2021-03-08 00:00:00","changedate":null}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function invoice1():array {
        return [
                'invoiceid'             => 4,
                'orderid'               => 4,
                'invoicenumber'         => 'INV004',
                'affiliatenr'           => 'testaff2',
                'paymentdate'           => '2021-03-02',
                'invoicedate'           => '2021-03-01',
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
                            ]],
                'shipping' =>   [[
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

}
