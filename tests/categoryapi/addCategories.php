<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Category;
use Externalshop\System\Utils\ArrayUtils;

class addCategories extends \PHPUnit\Framework\TestCase {

    public function setUp(): void
    {
        $this->deleteCategories();
    }

   /**
     * @dataProvider dataProviderCategories
     */
    public function testAddCategories(array $parms): void 
    {
        $processor = new Category($parms['clientid'], $parms['clientsecret']);
        $result    = $processor->add($this->categories());
        //print_r(json_encode($result));
        //die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 4);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderCategories() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	            [$parms1],
	           ];
    }

    private function deleteCategories() {
        $authentication = new Authentication();
        $processor      = new Category($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->deleteAll();
    }

    private function readResponse1() {
        return '{"data":[{"categoryid":"111","code":"CAT_001","name":"Tea"},{"categoryid":"112","code":"CAT_002","name":"Bread"},{"categoryid":"211","code":"CAT_003","name":"Cookies"},{"categoryid":"212","code":"CAT_004","name":"Vegetables"}],"message":"Your data is inserted successfully"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
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
