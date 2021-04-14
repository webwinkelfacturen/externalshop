<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\System\Utils\ArrayUtils;

class addCustomerTest extends \PHPUnit\Framework\TestCase {

   /**
     * @dataProvider dataProviderCustomer
     */
    public function testAddCustomer($parms) {
        $this->deleteCustomers();

        $processor = new Customer($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->add($this->customer1());
        print_r($result);
        die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderCustomer() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
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
        return '';
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
                'address1'       => 'Stationstraat 12',
                'zipcode'        => '1000 AA',
                'city'           => 'Amsterdam',
                'isocountry'     => 'NL',
                'mobile'         => '0612345678',
                'email'          => 'jean@mycompany.nl'
               ];

    }

}
