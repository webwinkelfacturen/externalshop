<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Tax;
use Externalshop\System\Utils\ArrayUtils;

//phpunit v7.5
class readTaxesTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addTaxes();
    }

   /**
     * @dataProvider dataProviderTax
     */
    public function testReadTaxes($parms) {
        $processor = new Tax($parms['clientid'], $parms['clientsecret']);

        $result    = json_decode($processor->readTaxes(), true);
        //print_r(json_encode($result, JSON_PRETTY_PRINT)); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0); 

        // validate 
        //$utils = new ArrayUtils();
        //$diff  = $utils->arrayDiff($result['data'], $parms['response'], ['id'] );
        //$diff = $diff[1];

        //$this->assertTrue( $diff['taxid']             === 'values_equals'         );
        //$this->assertTrue( $diff['percentage_1']      === 'values_equals'         );
        //$this->assertTrue( $diff['percentage_100']    === 'values_equals'         );
        //$this->assertTrue( $diff['title']             === 'values_equals'         );
        //$this->assertTrue( $diff['description']       === 'values_equals'         );
        //$this->assertTrue( $diff['country']           === 'values_equals'         );
        //$this->assertTrue( $diff['createddate']       === 'value_unavailable_new' );
        //$this->assertTrue( $diff['changedate']        === 'value_unavailable_new' );
        //$this->assertTrue( $diff['field_taxclass']    === 'value_unavailable_old' );
        //$this->assertTrue( $diff['field_taxcategory'] === 'value_unavailable_old' );
        //$this->assertTrue( $diff['field_isdefault']   === 'value_unavailable_old' );
        //$this->assertTrue( $diff['field_type']        === 'value_unavailable_old' );
        //$this->assertTrue( $diff['field_typename']    === 'value_unavailable_old' );
    }

    public function tearDown() {
        $this->deleteTaxes();
    }
	
    public function dataProviderTax() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];
        return [
	    [$parms1],
	];
    }

    private function addTaxes() {
        $authentication = new Authentication();
        $processor      = new Tax($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add([$this->tax1(), $this->tax2()]);
    }

    private function deleteTaxes() {
        $authentication = new Authentication();
        $processor      = new Tax($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $taxresponse    = $processor->readTaxes();
        $taxarray       = json_decode($taxresponse, true);
        $taxes          = [];
        if (is_array($taxarray) && array_key_exists('data', $taxarray)) {
            $taxes = $taxarray['data'];
        }

        foreach ($taxes as $tax) {
            $processor->delete($tax['taxid']);
        }
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id'], true);
        return $utils->noDifferences($diff);
    }

    private function readResponse1() {
        return '{"data":[{"taxid":"112","percentage_1":"0.0900","percentage_100":"9.0000","title":"testtax","description":null,"country":"BE","createddate":"2021-04-13 00:00:00","changedate":null},{"taxid":"212","percentage_1":"0.2100","percentage_100":"21.0000","title":"testtax","description":null,"country":"BE","createddate":"2021-04-13 00:00:00","changedate":null}],"message":"Result"}';
    }

    private function tax1() {
        $tax = [
                'taxid' => 112,
                'percentage' => 0.09,
                'percentage_100' => 9,
                'title' => 'testtax',
                'isdefault' => 0,
                'country' => 'BE',
                'type' => 'soort',
                'typename' => 'soortname'
               ];
       return $tax;
    }

    private function tax2() {
        $tax = [
                'taxid' => 212,
                'percentage' => 0.21,
                'percentage_100' => 21,
                'title' => 'testtax',
                'isdefault' => 0,
                'country' => 'BE',
                'type' => 'soort',
                'typename' => 'soortname'
               ];
       return $tax;
    }

}
