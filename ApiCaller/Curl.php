<?php


namespace ApiCaller;

class Curl{

    private $debug = 1;
    private $apiUrl = null;

    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function call($action, $parameters = null, $request, $headers, $type = "GET")
    {
        $url = $this->apiUrl .= $action;
        if ($parameters)
            $url .= "?".http_build_query($parameters, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($request) {
            print_r($request);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        $curl_result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($curl_result,true);
        $result['response_code']= $httpCode;
        return $result;
    }
}
