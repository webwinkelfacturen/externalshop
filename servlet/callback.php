<?php

require( dirname( __FILE__ ) . '/../../autoload.php' );

use Externalshop\System\HTTP\OAuth;
use WWF\Tools\Constants;
use WWF\Tools\StringUtils;

session_start();

$code  = determineCode($_GET, []);
if (empty($argv)) {
    $argv = [];
}
$code  = determineCode($_GET, $argv);
if ($code){
    $return = getTokenRequest($_GET, $argv);
    if (array_key_exists('error', $return) && strlen(trim($return['error'])) > 0) {
        $string = '';
        $string .= '<div>Er is iets mis gegaan met de authenticatie. Probeer het later nog eens';
        $string .= '<br><br>De melding is ' . $return['error'] . ' x ' . $return['error_description'];
        $string .= '</div>';
        echo $string;
        return $string;
    }

    print_r($return);
    $refreshtoken = $return['refresh_token'];
    $accesstoken  = $return['access_token'];
    writeTokens(['clientid', 'clientsecret'], [$refreshtoken, $accesstoken]);

    header("Location: " . determineNextpage());
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

function getTokenRequest(array $get, array $argv):array {
    if (count($get) > 0 && array_key_exists('code', $get)) {
        $externalshopoauth  = new OAuth();
        return $externalshopoauth->token_request($get['code']);
    }
    if (count($argv) > 1) {
        $externalshopoauth  = new OAuth();
        return $externalshopoauth->token_request($argv[1]);
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

function writeTokens($fnames,$fvalues) {
    $arr    = array_combine($fnames, $fvalues);
    $file   = dirname(__FILE__) . '/../../../../files/externalshop/oauth/user.cnf';

    $string = '';
    foreach ($arr as $key => $val) {
        $string .= $key . '=' . $val . "\r\n";
    }
    $return = file_put_contents($file, $string);
}

function determineNextpage():string {
    return 'https://secure136.cloudinvoice.company/api/v2/cloudinvoice/externalshop/view/success.php';
}

