<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Paymentmethod;
use Externalshop\System\Utils\ArrayUtils;

class readPaymentmethods extends \PHPUnit\Framework\TestCase {

   /**
     * @dataProvider dataProviderPaymentmethod
     */
    public function testReadPaymentmethods(array $parms): void
    {
        $this->addPaymentmethods();
        $processor = new Paymentmethod($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->readPaymentmethods();
	//print_r(json_encode($result)); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

    public function dataProviderPaymentmethod() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
	];
    }

    private function addPaymentmethods() {
        $this->deletePaymentmethods();
        $authentication = new Authentication();
        $processor      = new PaymentMethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->paymentmethods());
    }

    private function deletePaymentmethods() {
        $authentication = new Authentication();
        $processor      = new PaymentMethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
        return false;
    }

    private function readResponse1() {
        return '{"data":[{"paymentmethodid":"112","name":"ideal","type":"standard"},{"paymentmethodid":"113","name":"cash","type":"standard"}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, [], true);
        return $utils->noDifferences($diff);
    }

    private function paymentmethods() {
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
