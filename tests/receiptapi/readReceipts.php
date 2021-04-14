<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Receipt;
use Externalshop\System\Utils\ArrayUtils;

class readReceiptsTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addReceipts();
    }

    public function tearDown() {
        $this->deleteReceipts();
    }

   /**
     * @dataProvider dataProviderReceipt
     */
    public function testReadReceipts($parms) {
        $processor = new Receipt($parms['clientid'], $parms['clientsecret']);
        $result    = json_decode($processor->readReceipts($parms['startdate'], $parms['enddate']), true);
	//print_r(json_encode($result)); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderReceipt() {
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

    private function addReceipts() {
        $this->deleteReceipts();
        $authentication = new Authentication();
        $processor      = new Receipt($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $res1 = $processor->add($this->receipt1());
        $res2 = $processor->add($this->receipt2());
    }

    private function deleteReceipts() {
        $authentication = new Authentication();
        $processor      = new Receipt($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $receiptresponse  = $processor->readReceipts($startdate, $enddate);
        $receiptarray     = json_decode($receiptresponse, true);
        $receipts         = [];
        if (is_array($receiptarray) && array_key_exists('data', $receiptarray)) {
            $receipts = $receiptarray['data'];
        }

        foreach ($receipts as $receipt) {
            $processor->delete($receipt['receiptid']);
        }
    }

    private function readResponse1() {
        return '{"data":[{"id":"19","licensekey":"c4365634cedbe359e75020b9ae8b26","receiptid":"3","receiptnumber":"REC002","receiptdate":"2021-03-01 00:00:00","customerid":null,"affiliatenr":"testaff2","receiptstatus":null,"paymentstatus":null,"totalinclwithdiscount":"210.0000","totalexclwithdiscount":"233.0000","totalvatwithdiscount":"32.0000","totaldiscountincl":null,"totaldiscountexcl":null,"totaldiscountvat":null,"isicp":null,"isinternational":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null,"changedate":null,"creationdate":"2021-03-16 00:00:00"},{"id":"20","licensekey":"c4365634cedbe359e75020b9ae8b26","receiptid":"4","receiptnumber":"REC004","receiptdate":"2021-03-01 00:00:00","customerid":"3","affiliatenr":"testaff2","receiptstatus":null,"paymentstatus":null,"totalinclwithdiscount":"210.0000","totalexclwithdiscount":"233.0000","totalvatwithdiscount":"32.0000","totaldiscountincl":null,"totaldiscountexcl":null,"totaldiscountvat":null,"isicp":null,"isinternational":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null,"changedate":null,"creationdate":"2021-03-16 00:00:00"}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function receipt1() {
        return '{"receipt":"{\"receiptid\":3,\"receiptnumber\":\"REC002\",\"affiliatenr\":\"testaff2\",\"paymentdate\":\"2021-03-02\",\"receiptdate\":\"2021-03-01\",\"totalinclwithdiscount\":210,\"totalexclwithdiscount\":233,\"totalvatwithdiscount\":32,\"lines\":[{\"receiptid\":3,\"receiptlineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincl\":121,\"linepriceexcl\":100,\"linepricevat\":21,\"unitpriceincl\":60.5,\"unitpriceexcl\":50,\"unitpricevat\":10.5,\"taxpercentage\":21},{\"receiptid\":3,\"receiptlineid\":2,\"name\":\"productnaam3\",\"productcode\":\"sku3\",\"quantity\":2,\"linepriceincl\":109,\"linepriceexcl\":100,\"linepricevat\":9,\"unitpriceincl\":54.5,\"unitpriceexcl\":50,\"unitpricevat\":4.5,\"taxpercentage\":9}]}"}';
    }

    private function receipt2() {
       return '{"receipt":"{\"receiptid\":4,\"customerid\":3,\"receiptnumber\":\"REC004\",\"affiliatenr\":\"testaff2\",\"paymentdate\":\"2021-03-02\",\"receiptdate\":\"2021-03-01\",\"totalinclwithdiscount\":210,\"totalexclwithdiscount\":233,\"totalvatwithdiscount\":32,\"lines\":[{\"receiptid\":4,\"receiptlineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincl\":121,\"linepriceexcl\":100,\"linepricevat\":21,\"unitpriceincl\":60.5,\"unitpriceexcl\":50,\"unitpricevat\":10.5,\"taxpercentage\":21},{\"receiptid\":3,\"receiptlineid\":2,\"name\":\"productnaam3\",\"productcode\":\"sku3\",\"quantity\":2,\"linepriceincl\":109,\"linepriceexcl\":100,\"linepricevat\":9,\"unitpriceincl\":54.5,\"unitpriceexcl\":50,\"unitpricevat\":4.5,\"taxpercentage\":9}]}"}';
    }
}
