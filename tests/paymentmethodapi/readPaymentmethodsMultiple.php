<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Paymentmethod;
use Externalshop\System\Utils\ArrayUtils;

class readPaymentmethodsMultipleTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addPaymentmethods();
    }

   /**
     * @dataProvider dataProviderPaymentmethod
     */
    public function testReadPaymentmethods($parms) {
        $processor = new Paymentmethod($parms['clientid'], $parms['clientsecret']);
        $result    = json_decode($processor->readPaymentmethods(), true);
	//print_r(json_encode($result)); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

    public function tearDown() {
        $this->deletePaymentmethods();
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
        $authentication = new Authentication();
        $processor      = new PaymentMethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->addPaymentmethods($this->paymentmethods());
    }

    private function deletePaymentmethods() {
        $authentication = new Authentication();
        $processor      = new PaymentMethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $pmresponse     = $processor->readPaymentmethods();
        $pmarray        = json_decode($pmresponse, true);
        $pmethods       = [];
        if (is_array($pmarray) && array_key_exists('data', $pmarray)) {
            $pmethods = $pmarray['data'];
        }

        foreach ($pmethods as $pm) {
            $processor->delete($pm['paymentmethodid']);
        }
    }

    private function readResponse1() {
        return '{"data":[{"paymentmethodid":"112","name":"ideal","type":"standard"},{"paymentmethodid":"113","name":"paypal","type":"standard"}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, [], true);
        return $utils->noDifferences($diff);
    }

    private function paymentmethods() {
        $pms   = [];
        $pms[] = [
                  'paymentmethodid' => 112,
                  'name'            => 'ideal',
                  'type'            => 'standard',
                 ];
        $pms[] = [
                  'paymentmethodid' => 113,
                  'name'            => 'paypal',
                  'type'            => 'standard',
                 ];
        return json_encode(['paymentmethods' => json_encode($pms)]);
    }

}
