<?php //cikey

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require dirname(__FILE__) . '/../../autoload.php';

use Externalshop\Processor\Authentication;
use Externalshop\Processor\Closure;

$authentication = new Authentication();
$clientsecret   = $authentication->readValue('clientsecret');
$clientid       = $authentication->readValue('clientid');

$closure      = new Closure($clientid, $clientsecret);
echo createClosures();
//die();
$response     = $closure->add(createClosures());

print_r($response ); die;

function createClosures(){
    $closure = [
                'closureid'         => 2,
                'closurenumber'     => 'CLOS002',
                'closurename'       => 'Dagafsluiting 2021-03-16',
                'closuredate'       => '2021-03-16',
                'payments'          => create_paymentlines(),
                'productcategories' => create_turnoverlines()
               ];
    return json_encode(['closure' => json_encode($closure)]);
}

function create_paymentlines() {
    return [
            [
             'methodid'   => 5,
             'methodname' => 'betaalmet2',
             'currency'   => 'EUR',
             'total'      => 121,
            ],
            [
             'methodid'   => 3,
             'methodname' => 'Betaalmethod3',
             'currency'   => 'EUR',
             'total'      => 59,
            ]
           ];
}

function create_turnoverlines() {
    return [
            [
             'groupid'   => 4,
             'groupname' => 'Koffie',
             'currency'  => 'EUR',
             'lines'     => create_lines(1),
            ],
            [
             'groupid'   => 5,
             'groupname' => 'Taart',
             'currency'  => 'EUR',
             'lines'     => create_lines(2),
            ],
           ];
}

function create_lines($ind) {
    if ($ind == 1) {
        return [
                ['taxrate' => 0.21, 'taxvalue' => 10.50, 'lineexclvat' => 50],
                ['taxrate' => 0.09, 'taxvalue' => 0.90, 'lineexclvat' => 10],
               ];
    }
    if ($ind == 2) {
        return [
                ['taxrate' => 0.21, 'taxvalue' => 10.50, 'lineexclvat' => 50],
                ['taxrate' => 0.09, 'taxvalue' => 3.60, 'lineexclvat' => 40],
               ];
    }
}
