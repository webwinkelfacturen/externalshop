<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\Utils\Constants;

class OAuth {

    function authorize_request() {
        $constants     = new Constants();
        $oauthurl      = $constants->getWisteriaOAuthurl() . '/authorize';
        $redirect_uri  = $constants->getCallbackurl();

        $postparms     = 'type=cloudinvoice&redirect_url=' . rawurlencode($redirect_uri) . '&client_id='. $constants->getClientid() . '&client_secret=' . $constants->getClientsecret();
        return json_decode($this->doHttpRequest($oauthurl, $postparms), true);
    }

    function token_request(string $code) { 
        $constants     = new Constants();
        $oauthurl      = $constants->getWisteriaOAuthurl() . '/token';
        $redirect_uri  = $constants->getCallbackurl();

        $postparms     = 'code=' . $code . '&grant_type=authorization_code&type=external&redirect_url=' . rawurlencode($redirect_uri) . '&client_id='. $constants->getClientid() . '&client_secret=' . $constants->getClientsecret();
        return json_decode($this->doHttpRequest($oauthurl, $postparms), true);
    }

    function refreshtoken_request(UserAuth $userauth) { 
        $constants   = new Constants();
        $refreshurl  = $constants->getWisteriaOAuthurl() . '/token';
        $postparms   = 'grant_type=refresh_token&type=external&client_id=' . $constants->getClientid() . '&client_secret=' . $constants->getClientsecret() . '&refresh_token=' . $userauth->getClientid();
        $response    = json_decode($this->doHttpRequest($refreshurl, $postparms), true);

        $this->persist($response);
        return $response;
    }

    private function persist(array $response) {
         $oauthfile = dirname(__FILE__) . '/../../../../../files/externalshop/oauth/user.cnf';
         $string    = 'clientid=' . $response['refresh_token'] . "\r\n";
         $string   .= 'clientsecret=' . $response['access_token'] . "\r\n";
         file_put_contents($oauthfile, $string);
    }

    function doHttpRequest($url, $postparms) { 
        $ch = curl_init();
   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $header = array(
                        "Content-Type: application/x-www-form-urlencoded",
                       );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postparms );
        curl_setopt($ch, CURLOPT_POST, 1);
        $request_result = curl_exec($ch);
print_r($request_result);
       
        curl_close($ch);
        return $request_result;
    }

}
