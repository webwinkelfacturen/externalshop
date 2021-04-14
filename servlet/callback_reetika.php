<?php

require( dirname( __FILE__ ) . '/../../autoload.php' );

use Ikentoo\Processor\Connection;
use Ikentoo\System\HTTP\OAuth;
use Ikentoo\Tests\Stubs\SystemHTTP;
use CloudInvoice\Model\Credentials;
use WWF\Tools\StringUtils;

session_start();

if (!isset($argv)) {
    $argv = [];
}

$creds = determineCreds($_SESSION, $argv);
$cikey = $creds->getCloudinvoiceapikey();

$code  = determineCode($_GET, $argv);

if ($code){
    $return = getTokenRequest($creds, $_GET, $argv);

    if (array_key_exists('error', $return) && strlen(trim($return['error'])) > 0) {
        $string = '';
        $string .= '<div>Er is iets mis gegaan met de authenticatie. Probeer het later nog eens';
        $string .= '<br><br>De melding is ' . $return['error'] . ' x ' . $return['error_description'];
        $string .= '</div>';
        echo $string;
        return $string;
    }

    $accesstoken  = $return['access_token'];
    if (strlen(trim($creds->getContractcode())) == 0) {
        $creds->modify(['contract', 'tokensourcesecret'], ['daily_bookingtotals;' . getDefaultcontract($creds->getInvoicesystem()), $accesstoken]);
    } else {
        $creds->modify(['tokensourcesecret'], [$accesstoken]);
    }

    setShopid($creds, $argv);

    $creds->readCredentials($cikey);

    header("Location: " . determineNextpage($creds));
}

function determineCreds(array $session, array $argv):Credentials {
    $creds = new Credentials();
    if (is_array($session) && array_key_exists('cloudinvoiceapikey', $session)) {
        $cikey = $session['cloudinvoiceapikey'];
    }
    if (is_array($argv) && StringUtils::count_wwf($argv) > 2) {
        $cikey = $argv[1];
    }
    $creds->readCredentials($cikey);
    return $creds;
}

function determineCode(array $get, array $argv):string {
    if (is_array($get) && array_key_exists('code', $get)) {
        return $get['code'];
    }
    if (StringUtils::count_wwf($argv) > 1) {
        return $argv[1];
    }
    return 'nocode';
}

function getTokenRequest(Credentials $creds, array $get, array $argv):array {
    if (is_array($get) && array_key_exists('code', $get)) {
        $ikentooauth  = new OAuth();
        return $ikentooauth->token_request($creds, $get['code']);
    }
    if (StringUtils::count_wwf($argv) > 2) {
        return json_decode($argv[2], true);
    }
    return [];
}

function setShopid(Credentials $creds, array $argv) {
    $processor = new Connection($creds);
    if (StringUtils::count_wwf($argv) > 2) {
        $processor->setHTTP(new SystemHTTP());
    }
    $processor->extractAndStoreShopid();
}

function determineNextpage(Credentials $creds):string {
    $utils = new StringUtils();
    return 'https://uwkoppeling.webwinkelfacturen.nl/settings?cikey=' . $creds->getCloudinvoiceapikey() . '&signature=' . $utils->getSignature($creds->getCloudinvoiceapikey());
}

function getDefaultcontract(string $invoicesystem):string {
    if ($invoicesystem == 'exactonline') {
        return 'cashtransactions;eolincltax;exactonline2;';
    }
    if ($invoicesystem == 'imuisexactonline') {
        return 'imuistransactions;';
    }
    if ($invoicesystem == 'snelstart') {
        return 'cashtransactions;';
    }
    if ($invoicesystem == 'twinfield') {
        return 'cashtransactions;twinfield2;';
    }
    if ($invoicesystem == 'visma') {
        return 'vismavouchers;';
    }
    if ($invoicesystem == 'yuki') {
        return 'cashtransactions;';
    }
}

