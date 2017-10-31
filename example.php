<?php

require "autoload.php";

use ApiCaller\Curl;

const _API_URL_ = '<apiurl>';
const USER_ID = '<apikey>';
const API_KEY = '<apipass>';
const VERSION = '<version>';

class example{

    public function __construct()
    {
    }

    public function get_orders($created_after = null, $created_before = null, $updated_after = null, $updated_before = null,
                               $limit = null, $offset = null, $status = null, $sort_by = null, $sort_direction = null)
    {
        $data['Action'] = "GetOrders";
        if (!empty($created_after)) $data['CreatedAfter'] = $created_after;
        if (!empty($created_before)) $data['CreatedAfter'] = $created_before;
        if (!empty($updated_after)) $data['UpdatedAfter'] = $updated_after;
        if (!empty($updated_before)) $data['UpdatedBefore'] = $updated_before;
        if (!empty($limit)) $data['Limit'] = $limit;
        if (!empty($offset)) $data['Offset'] = $offset;
        if (!empty($status)) $data['Status'] = $status;
        if (!empty($sort_by)) $data['SortBy'] = $sort_by;
        if (!empty($sort_direction)) $data['SortDirection'] = $sort_direction;
        $parameters = $this->signature($data);
        $apiClient = new Curl(_API_URL_);
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        return $apiClient->call($data['Action'], $parameters, $headers, $type = "GET");
    }

    private function signature($data)
    {
        date_default_timezone_set('UTC');
        $now = new DateTime();
        $parameters = array(
            'UserID' => USER_ID,
            'Version' => VERSION,
            'Action' => $data['Action'],
            'Format' => 'JSON',
            'Timestamp' => $now->format(DateTime::ISO8601)
        );
        if (!empty($data['Filter'])) $parameters['Filter'] = $data['Filter'];
        if (!empty($data['Search'])) $parameters['Search'] = $data['Search'];
        if (!empty($data['Limit'])) $parameters['Limit'] = $data['Limit'];
        if (!empty($data['Offset'])) $parameters['Offset'] = $data['Offset'];
        if (!empty($data['PrimaryCategory'])) $parameters['PrimaryCategory'] = $data['PrimaryCategory'];
        if (!empty($data['AttributeSet'])) $parameters['AttributeSet'] = $data['AttributeSet'];
        if (!empty($data['OrderId'])) $parameters['OrderId'] = $data['OrderId'];
        if (!empty($data['OrderIdList'])) $parameters['OrderIdList'] = $data['OrderIdList'];
        if (!empty($data['OrderItemId'])) $parameters['OrderItemId'] = $data['OrderItemId'];
        if (!empty($data['OrderItemIds'])) $parameters['OrderItemIds'] = $data['OrderItemIds'];
        if (!empty($data['InvoiceNumber'])) $parameters['InvoiceNumber'] = $data['InvoiceNumber'];
        if (!empty($data['AccessKey'])) $parameters['AccessKey'] = $data['AccessKey'];
        if (!empty($data['DeliveryType'])) $parameters['DeliveryType'] = $data['DeliveryType'];
        if (!empty($data['ShippingProvider'])) $parameters['ShippingProvider'] = $data['ShippingProvider'];
        if (!empty($data['TrackingNumber'])) $parameters['TrackingNumber'] = $data['TrackingNumber'];
        if (!empty($data['Reason'])) $parameters['Reason'] = $data['Reason'];
        if (!empty($data['ReasonDetail'])) $parameters['ReasonDetail'] = $data['ReasonDetail'];
        if (!empty($data['DocumentType'])) $parameters['DocumentType'] = $data['DocumentType'];
        if (!empty($data['CreatedAfter'])) $parameters['CreatedAfter'] = $data['CreatedAfter'];
        if (!empty($data['CreatedBefore'])) $parameters['CreatedBefore'] = $data['CreatedBefore'];
        if (!empty($data['UpdatedAfter'])) $parameters['UpdatedAfter'] = $data['UpdatedAfter'];
        if (!empty($data['UpdatedBefore'])) $parameters['UpdatedBefore'] = $data['UpdatedBefore'];
        if (!empty($data['Offset'])) $parameters['Offset'] = $data['Offset'];
        if (!empty($data['Status'])) $parameters['Status'] = $data['Status'];
        if (!empty($data['SortBy'])) $parameters['SortBy'] = $data['SortBy'];
        if (!empty($data['SortDirection'])) $parameters['SortDirection'] = $data['SortDirection'];
        if (!empty($data['FeedID'])) $parameters['FeedID'] = $data['FeedID'];

        ksort($parameters);
        $encoded = array();
        foreach ($parameters as $name => $value) {
            $encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
        }
        $concatenated = implode('&', $encoded);
        $api_key = API_KEY;
        $parameters['Signature'] = rawurlencode(hash_hmac("sha256", $concatenated, $api_key, false));
        return $parameters;
    }
}

$api = new example();
$orders = $api->get_orders(null, null, null, null, 50, null, "pending", "created_at", "DESC");

print_r($orders);