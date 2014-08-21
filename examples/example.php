<?php

require __DIR__ . '/../CurlHttpClient.php';

/**
 * Example 1
 * /
$page = CurlHttpClient::get('www.qq.com');
var_dump($page);
//*/

/**
 * Example 2
 */

$curlHttpClient = CurlHttpClient::getInstance();
//$curlHttpClient->setCurlOption(CURLOPT_NOBODY, TRUE);
$ret = $curlHttpClient->getRequest('http://www.qq.com');
var_dump($curlHttpClient->getRawResponseHeader());
//var_dump($curlHttpClient->getResponseBody());

if ($curlHttpClient->getErrorCode() == CURLE_OK) {
    //var_dump($ret);
} else {
    var_dump($curlHttpClient->getErrorCode());
    var_dump($curlHttpClient->getErrorMsg());
}
