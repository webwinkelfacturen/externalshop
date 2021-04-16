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

class readClosuresTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->deleteClosures();
        $this->addClosures();
        $this->addCategories();
        $this->addPaymentmethods();
    }

   /**
     * @dataProvider dataProviderClosure
     */
    public function testReadClosures($parms) {
        $processor = new Closure($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->readClosures($parms['startdate'], $parms['enddate']);
	//print_r($result); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderClosure() {
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

    private function addClosures() {
        $this->deleteClosures();
        $authentication = new Authentication();
        $processor      = new Closure($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->closure1());
        $processor->add($this->closure2());
    }

    private function deleteClosures() {
        $authentication = new Authentication();
        $processor      = new Closure($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $closurearray   = $processor->readClosures($startdate, $enddate);
        $closures       = [];
        if (is_array($closurearray) && array_key_exists('data', $closurearray)) {
            $closures = $closurearray['data'];
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
        return '{"data":[{"closureid":"111","closurenumber":"CLS_001","closurename":"Dagafsluiting 2021-03-21","closuredate":"2021-03-21 00:00:00","currency":"EUR","registerid":"2","registername":"Toonbank"},{"closureid":"112","closurenumber":"CLS_002","closurename":"Dagafsluiting 2021-03-22","closuredate":"2021-03-22 00:00:00","currency":"EUR","registerid":"2","registername":"Toonbank"}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function closure1():array {
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
                                          'taxValue' => 21.00,
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

    private function closure2():array {
        return [
                 'closureid'     => 112,
                 'closurenumber' => 'CLS_002',
                 'closurename'   => 'Dagafsluiting 2021-03-22',
                 'closuredate'   => '2021-03-22',
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
                                          'groupid' => 4,
                                          'lineExcl' => 100,
                                          'taxValue' => 21.00,
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
