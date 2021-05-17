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

   /**
     * @dataProvider dataProviderTax
     */
    public function testReadTaxes($parms) {
        $this->addTaxes($parms['jsonencode']);

        $processor = new Tax($parms['clientid'], $parms['clientsecret']);

        $result    = json_decode($processor->readTaxes(), true);
        //print_r($result);
        //die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0); 
    }

    public function dataProviderTax() {
        $authentication         = new Authentication();
        $parms1['clientid']     = $authentication->readValue('clientid');
        $parms1['clientsecret'] = $authentication->readValue('clientsecret');
        $parms1['jsonencode']   = false;
        $parms1['response']     = json_decode($this->readResponse1(), true)['data'];

        $parms2['clientid']     = $authentication->readValue('clientid');
        $parms2['clientsecret'] = $authentication->readValue('clientsecret');
        $parms2['jsonencode']   = true;
        $parms2['response']     = json_decode($this->readResponse1(), true)['data'];

        return [
	    [$parms1],
	    [$parms2],
	];
    }

    private function addTaxes(bool $jsonencode) {
        $this->deleteTaxes();
        $authentication = new Authentication();
        $processor      = new Tax($authentication->readValue('clientid'), $authentication->readValue('clientsecret'), $jsonencode);
        $result = $processor->add([$this->tax1(), $this->tax2()]);
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
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'createddate', 'changedate'], true);
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
