<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Closure;
use Externalshop\System\Utils\ArrayUtils;

class readClosuresTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        $this->addClosures();
    }

    public function tearDown() {
        $this->deleteClosures();
    }

   /**
     * @dataProvider dataProviderClosure
     */
    public function testReadClosures($parms) {
        $processor = new Closure($parms['clientid'], $parms['clientsecret']);
        $result    = json_decode($processor->readClosures($parms['startdate'], $parms['enddate']), true);
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
        $closureresponse  = $processor->readClosures($startdate, $enddate);
        $closurearray     = json_decode($closureresponse, true);
        $closures         = [];
        if (is_array($closurearray) && array_key_exists('data', $closurearray)) {
            $closures = $closurearray['data'];
        }

        foreach ($closures as $closure) {
            $processor->delete($closure['closureid']);
        }
    }

    private function readResponse1() {
        return '{"data":[{"closureid":"1","closurenumber":"CLOS001","closurename":"Dagafsluiting 2021-03-16","closuredate":"2021-03-16 00:00:00","registerid":null,"registername":null,"changedate":null},{"closureid":"2","closurenumber":"CLOS002","closurename":"Dagafsluiting 2021-03-16","closuredate":"2021-03-16 00:00:00","registerid":null,"registername":null,"changedate":null}],"message":"Result"}';
    }

    private function validate(array $trx1, array $trx2):string {
        $utils = new ArrayUtils();
        $diff  = $utils->arrayDiff($trx1, $trx2, ['id', 'licensekey', 'creationdate', 'changedate'], true);
        return $utils->noDifferences($diff);
    }

    private function closure1() {
        return '{"closure":"{\"closureid\":1,\"closurenumber\":\"CLOS001\",\"closurename\":\"Dagafsluiting 2021-03-16\",\"closuredate\":\"2021-03-16\",\"payments\":[{\"methodid\":5,\"methodname\":\"betaalmet2\",\"currency\":\"EUR\",\"total\":121},{\"methodid\":3,\"methodname\":\"Betaalmethod3\",\"currency\":\"EUR\",\"total\":59}],\"productcategories\":[{\"groupid\":4,\"groupname\":\"Koffie\",\"currency\":\"EUR\",\"lines\":[{\"taxrate\":0.21,\"taxvalue\":10.5,\"lineexclvat\":50},{\"taxrate\":0.09,\"taxvalue\":0.9,\"lineexclvat\":10}]},{\"groupid\":5,\"groupname\":\"Taart\",\"currency\":\"EUR\",\"lines\":[{\"taxrate\":0.21,\"taxvalue\":10.5,\"lineexclvat\":50},{\"taxrate\":0.09,\"taxvalue\":3.6,\"lineexclvat\":40}]}]}"}';
    }

    private function closure2() {
       return '{"closure":"{\"closureid\":2,\"closurenumber\":\"CLOS002\",\"closurename\":\"Dagafsluiting 2021-03-16\",\"closuredate\":\"2021-03-16\",\"payments\":[{\"methodid\":5,\"methodname\":\"betaalmet2\",\"currency\":\"EUR\",\"total\":121},{\"methodid\":3,\"methodname\":\"Betaalmethod3\",\"currency\":\"EUR\",\"total\":59}],\"productcategories\":[{\"groupid\":4,\"groupname\":\"Koffie\",\"currency\":\"EUR\",\"lines\":[{\"taxrate\":0.21,\"taxvalue\":10.5,\"lineexclvat\":50},{\"taxrate\":0.09,\"taxvalue\":0.9,\"lineexclvat\":10}]},{\"groupid\":5,\"groupname\":\"Taart\",\"currency\":\"EUR\",\"lines\":[{\"taxrate\":0.21,\"taxvalue\":10.5,\"lineexclvat\":50},{\"taxrate\":0.09,\"taxvalue\":3.6,\"lineexclvat\":40}]}]}"}';
    }
}
