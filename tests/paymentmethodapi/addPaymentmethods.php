<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Customer;
use Externalshop\Processor\Paymentmethod;
use Externalshop\System\Utils\ArrayUtils;

class addPaymentmethodesTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->deletePaymentmethodes();
    }

   /**
     * @dataProvider dataProviderPaymentmethodes
     */
    public function testAddPaymentmethodes($parms) {
        $processor = new Paymentmethod($parms['clientid'], $parms['clientsecret']);
        $result    = json_decode($processor->add($this->paymentmethods()), true);
	//print_r($result); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderPaymentmethodes() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
	];
    }

    private function deletePaymentmethodes() {
        $authentication = new Authentication();
        $processor      = new Paymentmethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
    }

    private function readResponse1() {
        return '{"data":[{"paymentmethodid":"112","name":"ideal","type":"standard"},{"paymentmethodid":"113","name":"cash","type":"standard"}],"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function paymentmethods():array {
        return [
                [
                 'paymentmethodid' => 112,
                 'name'            => 'ideal',
                 'type'            => 'standard',
                ],
                [
                 'paymentmethodid' => 113,
                 'name'            => 'cash',
                 'type'            => 'standard',
                ]
               ];
    }

}
