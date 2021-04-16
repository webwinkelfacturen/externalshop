<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Category;
use Externalshop\Processor\Closure;
use Externalshop\Processor\Paymentmethod;
use Externalshop\System\Utils\ArrayUtils;

class addClosuresTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
    }

   /**
     * @dataProvider dataProviderClosures
     */
    public function testAddClosures($parms) {
        //$this->deleteAllClosures();
        $this->deleteClosures();

        $this->addCategories();
        $this->addPaymentmethods();

        $processor = new Closure($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->add($this->closures());
	//print_r($result); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));

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

    private function deleteAllClosures() {
        $authentication = new Authentication();
        $processor      = new Closure($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
    }

    private function deleteClosures() {
        $authentication = new Authentication();
        $processor      = new Closure($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2020-01-01';
        $enddate        = date('Y-m-d');
        $responsearray  = $processor->readClosures($startdate, $enddate);
        $closures         = [];
        if (is_array($responsearray) && array_key_exists('data', $responsearray)) {
            $closures = $responsearray['data'];
        }

        foreach ($closures as $closure) {
            $processor->delete($closure['closureid']);
        }
    }

    private function addCategories() {
        $this->deleteCategories();
        $authentication = new Authentication();
        $processor      = new Category($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->categories());
    }

    private function deleteCategories() {
        $authentication = new Authentication();
        $processor      = new Category($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
    }

    private function addPaymentmethods() {
        $this->deletePaymentmethods();
        $authentication = new Authentication();
        $processor      = new Paymentmethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->paymentmethods());
    }

    private function deletePaymentmethods() {
        $authentication = new Authentication();
        $processor      = new Paymentmethod($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
    }

    private function readResponse1() {
        return '{"data":{"closureid":"111","closurenumber":"CLS_001","closurename":"Dagafsluiting 2021-03-21","closuredate":"2021-03-21 00:00:00","currency":"EUR","registerid":"2","registername":"Toonbank","payments":[{"closureid":"111","methodid":"1","methodname":null,"total":"54.5"},{"closureid":"111","methodid":"2","methodname":null,"total":"121"}],"productcategories":[{"groupid":"1","currency":null,"lineExcl":"50.0000","taxValue":"4.5000","taxRate":"9.0000"},{"groupid":"2","currency":null,"lineExcl":"50.0000","taxValue":"4.5000","taxRate":"9.0000"},{"groupid":"3","currency":null,"lineExcl":"100.0000","taxValue":"4.5000","taxRate":"21.0000"}]},"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function closures():array {
        return [
                 'closureid'     => 111,
                 'closurenumber' => 'CLS_001',
                 'closurename'   => 'Dagafsluiting 2021-03-21',
                 'closuredate'   => '2021-03-21',
                 'registerid'    => '2',
                 'registername'  => 'Toonbank',
                 'currency'      => 'EUR',
                 'productcategories' => [
                                         [
                                          'groupid' => 1,
                                          'lineExcl' => 50,
                                          'taxValue' => 4.50,
                                          'taxRate' => 9
                                         ],
                                         [
                                          'groupid' => 2,
                                          'lineExcl' => 50,
                                          'taxValue' => 4.50,
                                          'taxRate' => 9
                                         ],
                                         [
                                          'groupid' => 3,
                                          'lineExcl' => 100,
                                          'taxValue' => 4.50,
                                          'taxRate' => 21.00
                                         ],
                                        ],
                 'payments' => [
                                [
                                 'methodid' => 1,
                                 'total'    => 54.50
                                ],
                                [
                                 'methodid' => 2,
                                 'total'    => 121.00
                                ],
                               ]
               ];
    }

    private function categories():array {
        return [
                [
                 'categoryid' => 1,
                 'code'       => 'CAT_001',
                 'name'       => 'Tea'
                ],
                [
                 'categoryid' => 2,
                 'code'       => 'CAT_002',
                 'name'      => 'Bread'
                ],
                [
                 'categoryid' => 3,
                 'code'       => 'CAT_003',
                 'name'       => 'Cookies'
                ],
                [
                 'categoryid' => 4,
                 'code'       => 'CAT_004',
                 'name'       => 'Vegetables'
                ]
               ];
    }

    private function paymentmethods():array {
        return [
                [
                 'paymentmethodid' => 1,
                 'name'            => 'ideal',
                 'type'            => 'standard',
                ],
                [
                 'paymentmethodid' => 2,
                 'name'            => 'cash',
                 'type'            => 'standard',
                ]
               ];
    }

}
