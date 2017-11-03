<?php

namespace HttpApiClient;

class ApiClient{

    private $debug = 1;
    private $apiUrl = null;

    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function call($action, $parameters = null, $request = null, $headers = null, $cookie = null, $type = "GET",
                         $ssl = false)
    {
        $url = $this->apiUrl .= $action;
        if ($parameters) {
            $url .= "?" . http_build_query($parameters, '', '&');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if ($request) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        $curl_result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($curl_result, 0, $header_size);
        $body = substr($curl_result, $header_size);
        $result['httpCode']= $httpCode;
        $result['header']= $this::getHeaders($header);
        $result['body']= $body;
        curl_close($ch);

        return $result;
    }

    private static function getHeaders($header)
    {
        $headers = [];
        $lines = explode("\n",$header);
        foreach ($lines as $line) {
            $dotsPosition = strpos($line,":");
            if ($dotsPosition !== false) {
                $headerName = substr($line,0, $dotsPosition);
                $headerValue = substr($line, $dotsPosition+2, strlen($line));
                $headers[$headerName] = $headerValue;
            }
        }

        return $headers;
    }

    public static function getCookies($delimiter, $fullCookie){
        $cookies = explode($delimiter, $fullCookie);
        $cookies = $cookies;

        return $cookies;
    }

    public static function timeToWait($headers)
    {
        $waitingTime = 0;
        $remainingCalls = null;
        $remainingTime = null;
        foreach ($headers as $index => $value){
            $rateLimitHeader = strtolower(str_replace("-","", $index));
            switch ($rateLimitHeader) {
                case "xratelimitremaning":
                    $remainingCalls = $value;
                    break;
                case "xratelimitreset":
                    $remainingTime = $value;
                    break;
                default:
                    break;
            }
        }
        if (!empty($remainingCalls) && !empty($remainingTime)){
            $waitingTime = $remainingTime/$remainingCalls;
        }

        return $waitingTime;
    }




}
