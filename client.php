<?php
class client{
    private $debug = 1;

    private function curl($url, $parameters = null, $request, $contentType = "application/json", $type = "GET")
    {
        if ($parameters)
            $url .= "?".http_build_query($parameters, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.$contentType));
        if ($request) {
            print_r($request);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        $curl_result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($curl_result,true);
        $result['response_code']=http_response_code();
        return $result;
    }

}