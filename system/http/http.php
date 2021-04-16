<?php

namespace Externalshop\System\HTTP;

use Externalshop\Model\UserAuth;
use Externalshop\System\HTTP\OAuth;
use Externalshop\System\Utils\Constants;

class HTTP {

    function send_msg(UserAuth $user, string $url, string $method, array $data = []):string {
        try {
            $accesstoken = $this->getToken($user);

            $data['signature'] = $this->determineSignature($accesstoken, $data);

            $header[1] = 'Accept: application/json';
            $header[2] = 'Content-Type: application/x-www-form-urlencoded';
                
            $header[3]   = 'Authorization: Bearer '  . $accesstoken;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_VERBOSE, false);

            if ($method == 'POST') {
                curl_setopt($curl, CURLOPT_POST, 1);
            }
            if ($method == 'DELETE') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            if ($data) {
                if (in_array($method, ['DELETE', 'POST'])) { 
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $data )  );
                } else {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
            }
        
            $result = curl_exec($curl);
  
            //error_log("\n\rresponse\n\r");
            //error_log(print_r($result, true));
            curl_close($curl);

            return (string)$result;
              
        } catch (\Exception $e) {
            return error_log('error wisteria send_msg system-http-http ' . $e->getMessage());
        }             
        
           
    }
            
    private function getToken(UserAuth $user):string { 
        $oauth = new OAuth();
        $array = $oauth->refreshtoken_request($user);
 
        if (array_key_exists('access_token', $array)) {
            $user->modify( ['clientid' => $array['refresh_token'], 'clientsecret' => $array['access_token']] );
            return $array['access_token'];
        } else {
            //error_log('wisteria get token ' . $user->getCloudinvoiceapikey()); error_log(print_r($array, true));
        }
        return '';
    }
    
    private function determineSignature(string $token, array $array):string {
        $constants = new Constants();
        //return md5( gmdate("Ydm").gmdate("dmY").$constants->getClientsecret().json_encode($array).gmdate("dmY") );
        return md5( gmdate("Ydm").gmdate("dmY").$constants->getClientsecret().(string)reset($array).gmdate("dmY") );
    }
}
