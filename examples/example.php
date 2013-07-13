<?php

require __DIR__ . '/../CurlHttpClient.php';

/**
 * Example 1
 */
$page = CurlHttpClient::get('www.qq.com');

echo $page;