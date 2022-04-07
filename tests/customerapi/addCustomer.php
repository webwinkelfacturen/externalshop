<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\System\Utils\ArrayUtils;

class addCustomer extends \PHPUnit\Framework\TestCase {

   /**
     * @dataProvider dataProviderCustomer
     */
    public function testAddCustomer(array $parms): void
    {
        $this->deleteCustomers();

        $processor = new Customer($parms['clientid'], $parms['clientsecret'], $parms['jsonencode']);
        $result    = $processor->add($this->customer1());
        print_r($result);
        die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderCustomer() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['jsonencode']   = false;
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];

        $parms2['clientid']     = $authentication->readValue('clientid');
        $parms2['clientsecret'] = $authentication->readValue('clientsecret');
        $parms2['jsonencode']   = true;
        $parms2['response']     = json_decode($this->readResponse1(), true)['data'];

        return [
	    [$parms1],
	    [$parms2],
	];
    }

    private function deleteCustomers() {
        $authentication   = new Authentication();
        $processor        = new Customer($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $customerarray    = $processor->readCustomers();
        $customers        = [];
        if (is_array($customerarray) && array_key_exists('data', $customerarray)) {
            $customers = $customerarray['data'];
        }

        foreach ($customers as $customer) {
            $processor->delete($customer['customerid']);
        }

    }

    private function readResponse1() {
        return '{"data":{"customerid":"1","orderid":"","customernumber":"CUST001","firstname":"Jean","lastname":"Doe","company":"Grocery online","address1":"Stationstraat 12","address2":null,"housenr":null,"zipcode":"1000 AA","city":"Amsterdam","state":null,"country":null,"isocountry":"NL","kvk":null,"btwnr":null,"telnr":null,"mobile":"0612345678","email":"jean@mycompany.nl","iscompany":null,"isicp":null,"isinternational":null,"incltax":null},"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function customer1():array {
        return [
                'customerid'     => 1,
                'customernumber' => 'CUST001',
                'firstname'      => 'Jean',
                'lastname'       => 'Doe',
                'company'        => 'Grocery online',
                'address'        => 'Stationstraat 12',
                'zipcode'        => '1000 AA',
                'city'           => 'Amsterdam',
                'isocountry'     => 'NL',
                'mobile'         => '0612345678',
                'email'          => 'jean@mycompany.nl'
               ];

    }

}
