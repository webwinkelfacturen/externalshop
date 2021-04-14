<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Closure;
use Externalshop\System\Utils\ArrayUtils;

class addClosuresTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->deleteClosures();
    }

   /**
     * @dataProvider dataProviderClosures
     */
    public function testAddClosures($parms) {
        $processor = new Closure($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->add($this->closures());
	print_r($result); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 4);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderClosures() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
	];
    }

    private function deleteClosures() {
        $authentication = new Authentication();
        $processor      = new Closure($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
    }

    private function readResponse1() {
        return '';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function closures():array {
        return [
                [
                 'closureid'     => 111,
                 'closurenumber' => 'CLS_001',
                 'closurename'   => 'Dagafsluiting 2021-03-21',
                 'closurenumber' => '2021-03-21',
                 'registerid'    => '2',
                 'registername'  => 'Toonbank',
                 'currency'      => 'EUR',
                 'categorylines' => [
                                     [
                                     ] 
                                    ],
                 'paymentlines'  => [
                                     [
                                     ],
                                    ]
                ],
               ];
    }

}
