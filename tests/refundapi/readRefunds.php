<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Refund;
use Externalshop\System\Utils\ArrayUtils;

class readRefundsTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addRefunds();
    }

    public function tearDown() {
        $this->deleteRefunds();
    }

   /**
     * @dataProvider dataProviderRefund
     */
    public function testReadRefunds($parms) {
        $processor = new Refund($parms['clientid'], $parms['clientsecret']);
        $result    = json_decode($processor->readRefunds($parms['startdate'], $parms['enddate']), true);
	//print_r(json_encode($result)); die();

        $this->assertTrue(array_key_exists('data', $result));
        $this->assertTrue(is_array($result['data']));
        $this->assertTrue(count($result['data']) == 2);

        $diff1 = $this->validate($result['data'], $parms['response']);
        $this->assertTrue(strlen($diff1) == 0);
    }

	
    public function dataProviderRefund() {
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

    private function addRefunds() {
        $this->deleteRefunds();
        $authentication = new Authentication();
        $processor      = new Refund($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $processor->add($this->refunds1());
        $processor->add($this->refunds2());
    }

    private function deleteRefunds() {
        $authentication = new Authentication();
        $processor      = new Refund($authentication->readValue('clientid'), $authentication->readValue('clientsecret'));
        $startdate      = '2021-03-01';
        $enddate        = date('Y-m-d');
        $refundsresponse  = $processor->readRefunds($startdate, $enddate);
        $refundsarray     = json_decode($refundsresponse, true);
        $refundss         = [];
        if (is_array($refundsarray) && array_key_exists('data', $refundsarray)) {
            $refundss = $refundsarray['data'];
        }

        foreach ($refundss as $refunds) {
            $processor->delete($refunds['refundid']);
        }
    }

    private function readResponse1() {
        return '{"data":[{"id":"13","licensekey":"c4365634cedbe359e75020b9ae8b26","refundid":"4","refundnumber":"REF004","orderid":"3","customerid":"1","ordernumber":"ORD003","affiliatenr":"testaff2","refundstatus":null,"refunddate":"2021-03-01 00:00:00","paymentstatus":null,"totalinclwithdiscount":"210.0000","totalexclwithdiscount":"233.0000","totalvatwithdiscount":"32.0000","totaldiscountincl":null,"totaldiscountexcl":null,"totaldiscountvat":null,"isicp":null,"isinternational":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null,"creationdate":"2021-03-16 00:00:00","changedate":null},{"id":"14","licensekey":"c4365634cedbe359e75020b9ae8b26","refundid":"5","refundnumber":"REF005","orderid":"3","customerid":"1","ordernumber":"ORD003","affiliatenr":"testaff2","refundstatus":null,"refunddate":"2021-03-01 00:00:00","paymentstatus":null,"totalinclwithdiscount":"210.0000","totalexclwithdiscount":"233.0000","totalvatwithdiscount":"32.0000","totaldiscountincl":null,"totaldiscountexcl":null,"totaldiscountvat":null,"isicp":null,"isinternational":null,"pdf":null,"duedate":null,"duedays":null,"totalpaid":null,"totalunpaid":null,"creationdate":"2021-03-16 00:00:00","changedate":null}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function refunds1() {
        return '{"refund":"{\"refundid\":4,\"refundnumber\":\"REF004\",\"customerid\":1,\"orderid\":3,\"ordernumber\":\"ORD003\",\"affiliatenr\":\"testaff2\",\"paymentdate\":\"2021-03-02\",\"refunddate\":\"2021-03-01\",\"totalinclwithdiscount\":210,\"totalexclwithdiscount\":233,\"totalvatwithdiscount\":32,\"lines\":[{\"refundid\":4,\"refundlineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincl\":121,\"linepriceexcl\":100,\"linepricevat\":21,\"unitpriceincl\":60.5,\"unitpriceexcl\":50,\"unitpricevat\":10.5,\"discountincl\":0,\"discountexcl\":0,\"discountvat\":0,\"taxpercentage\":21},{\"refundid\":3,\"refundlineid\":2,\"name\":\"productnaam3\",\"productcode\":\"sku3\",\"quantity\":2,\"linepriceincl\":109,\"linepriceexcl\":100,\"linepricevat\":9,\"unitpriceincl\":54.5,\"unitpriceexcl\":50,\"unitpricevat\":4.5,\"discountincl\":0,\"discountexcl\":0,\"discountvat\":0,\"taxpercentage\":9}],\"customer\":{\"customerid\":1,\"customernumber\":\"klantnummer\",\"firstname\":\"voornaam\",\"lastname\":\"achternaam\",\"company\":\"bedrijfsnaam\",\"address1\":\"adresregel1\",\"address2\":\"adresregel2\",\"housenr\":\"huisnr\",\"zipcode\":\"1000 AA\",\"city\":\"Amsterdam\",\"countryname\":\"Nederland\",\"isocountry\":\"NL\",\"mobile\":\"312309324342\",\"email\":\"test@gmail.com\"}}"}';
    }

    private function refunds2() {
       return '{"refund":"{\"refundid\":5,\"refundnumber\":\"REF005\",\"customerid\":1,\"orderid\":3,\"ordernumber\":\"ORD003\",\"affiliatenr\":\"testaff2\",\"paymentdate\":\"2021-03-02\",\"refunddate\":\"2021-03-01\",\"totalinclwithdiscount\":210,\"totalexclwithdiscount\":233,\"totalvatwithdiscount\":32,\"lines\":[{\"refundid\":5,\"refundlineid\":1,\"name\":\"productnaam2\",\"productcode\":\"sku2\",\"quantity\":1,\"linepriceincl\":121,\"linepriceexcl\":100,\"linepricevat\":21,\"unitpriceincl\":60.5,\"unitpriceexcl\":50,\"unitpricevat\":10.5,\"discountincl\":0,\"discountexcl\":0,\"discountvat\":0,\"taxpercentage\":21},{\"refundid\":3,\"refundlineid\":2,\"name\":\"productnaam3\",\"productcode\":\"sku3\",\"quantity\":2,\"linepriceincl\":109,\"linepriceexcl\":100,\"linepricevat\":9,\"unitpriceincl\":54.5,\"unitpriceexcl\":50,\"unitpricevat\":4.5,\"discountincl\":0,\"discountexcl\":0,\"discountvat\":0,\"taxpercentage\":9}],\"customer\":{\"customerid\":1,\"customernumber\":\"klantnummer\",\"firstname\":\"voornaam\",\"lastname\":\"achternaam\",\"company\":\"bedrijfsnaam\",\"address1\":\"adresregel1\",\"address2\":\"adresregel2\",\"housenr\":\"huisnr\",\"zipcode\":\"1000 AA\",\"city\":\"Amsterdam\",\"countryname\":\"Nederland\",\"isocountry\":\"NL\",\"mobile\":\"312309324342\",\"email\":\"test@gmail.com\"}}"}';
    }
}
