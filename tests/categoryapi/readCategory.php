<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Category;
use Externalshop\System\Utils\ArrayUtils;

class readCategoryTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addCategories();
    }

   /**
     * @dataProvider dataProviderCategory
     */
    public function testReadCategory($parms) {
        $processor = new Category($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->readCategory($parms['id']);
	//print_r(json_encode($result)); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderCategory() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['id']           = 111;
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
	];
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
        $startdate      = '2020-01-01';
        $enddate        = date('Y-m-d');
        $categoryarray  = $processor->readCategories($startdate, $enddate);

        $processor->deleteAll();
    }

    private function readResponse1() {
        return '{"data":{"categoryid":"111","code":"CAT_001","name":"Tea"},"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'createddate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function categories():array {
          return [
                  [
                   'categoryid' => 111,
                   'code'       => 'CAT_001',
                   'name'       => 'Tea'
                  ],
                  [
                   'categoryid' => 112,
                   'code'       => 'CAT_002',
                   'name'      => 'Bread'
                  ],
                  [
                   'categoryid' => 211,
                   'code'       => 'CAT_003',
                   'name'       => 'Cookies'
                  ],
                  [
                   'categoryid' => 212,
                   'code'       => 'CAT_004',
                   'name'       => 'Vegetables'
                  ]
                 ];
    }

}
