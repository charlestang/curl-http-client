<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Charles Tang<charlestang@foxmail.com>
 *
 * Permission is hereby granted, free of charge, to any person 
 * obtaining a copy of this software and associated documentation 
 * files (the "Software"), to deal in the Software without 
 * restriction, including without limitation the rights to use, 
 * copy, modify, merge, publish, distribute, sublicense, and/or 
 * sell copies of the Software, and to permit persons to whom 
 * the Software is furnished to do so, subject to the following 
 * conditions:
 *
 * The above copyright notice and this permission notice shall 
 * be included in all copies or substantial portions of the 
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY 
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE 
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS 
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE 
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * This is a wrapper class of cURL extension for php
 * @author Charles Tang<charlestang@foxmail.com>
 * @version 1.0
 */
class CurlHttpClient {

    /**
     * @var CurlHttpClient 
     */
    private static $_curlHttpClient = null;

    /**
     * @var array default options should set to cURL 
     */
    private static $_defaultOptions = array(
        CURLOPT_RETURNTRANSFER       => TRUE, //return the result
        CURLOPT_SSL_VERIFYPEER       => FALSE, //ignore SSL
        CURLOPT_CONNECTTIMEOUT       => 1, //connect timeout, default 1s
        CURLOPT_TIMEOUT              => 8, //request timeout, default 8s
        CURLOPT_USERAGENT            => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1) Gecko/20100101 Firefox/22.0',
        CURLOPT_DNS_USE_GLOBAL_CACHE => FALSE, //this option is not thread safe
        //ipv6 will cause some trouble in 64bit server, so use ipv4 as default
        CURLOPT_IPRESOLVE            => CURL_IPRESOLVE_V4,
    );

    /**
     * @var resource cURL instance
     */
    private $_curl = null;

    /**
     * @var array the options container 
     */
    private $_options = array();

    /**
     * @var string the request url
     */
    private $_url = '';

    /**
     * @var array the headers container 
     */
    private $_headers = array();

    /**
     * @var array the cookies container 
     */
    private $_cookies = array();

    /**
     * @var array the request params container 
     */
    private $_queries = array();

    /**
     * @var int the error code cURL handler returned 
     */
    private $_errorCode = 0;

    /**
     * @var string the error message the cURL handler returned 
     */
    private $_errorMsg = '';

    /**
     * @var string the response header of the request 
     */
    private $_responseHeaderStr = '';

    /**
     * @var string the response body of the request 
     */
    private $_responseBody = '';

    /**
     * @var array the parsed result of the response header string 
     */
    private $_responseHeaders = array();

    /**
     * Constructor
     * @throws Exception
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the object, the method should be called only once
     * @throws Exception
     */
    protected function init() {
        $this->free();
        $this->_curl = curl_init();
        if ($this->_curl === false) {
            throw new Exception("cURL init failed.");
        }
        if (!curl_setopt_array($this->_curl, self::$_defaultOptions)) {
            throw new Exception("cURL default options set failed.");
        }
    }

    /**
     * Free all the resources used by this object
     */
    protected function free() {
        $this->_options = array();
        $this->_url = '';
        $this->_headers = array();
        $this->_cookies = array();
        $this->_queries = array();
        $this->_errorCode = 0;
        $this->_errorMsg = '';
        $this->_responseHeaderStr = '';
        $this->_responseBody = '';
        $this->_responseHeaders = array();
        if (is_resource($this->_curl) && get_resource_type($this->_curl) == 'curl') {
            curl_close($this->_curl);
        }
        $this->_curl = null;
    }

    /**
     * Re-create the curl resource, and initialize all the member variables
     * @throws Exception
     */
    public function reset() {
        $this->free();
        $this->init();
    }

    /**
     * The real request sent out in this method
     * @return boolean
     */
    protected function doRequest() {
        curl_setopt_array($this->_curl, $this->_options);

        $withoutBody = array_key_exists(CURLOPT_NOBODY, $this->_options) && $this->_options[CURLOPT_NOBODY] == true;

        //set headers information
        if (!empty($this->_headers)) {
            $headers = array();
            foreach ($this->_headers as $key => $value) {
                array_push($headers, "$key: $value");
            }
            curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $headers);
        }

        //set cookies information
        if (!empty($this->_cookies)) {
            $cookies = '';
            foreach ($this->_cookies as $key => $value) {
                $cookies .= "$key=$value; ";
            }
            curl_setopt($this->_curl, CURLOPT_COOKIE, trim($cookies, '; '));
        }

        curl_setopt($this->_curl, CURLOPT_URL, $this->_url);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curl, CURLOPT_HEADER, true);

        $this->_errorCode = 0;
        $this->_errorMsg = '';
        $this->_responseHeaderStr = '';
        $this->_responseBody = '';

        $ret = curl_exec($this->_curl);
        if (false !== $ret) {
            $response = explode("\r\n\r\n", $ret, 2);
            $this->_responseHeaderStr = $response[0];
            $this->_responseBody = $response[1];
        } else {
            $this->_errorCode = curl_errno($this->_curl);
            $this->_errorMsg = curl_error($this->_curl);
        }

        return $this->_errorCode === 0;
    }

    /**
     * Send the request use HTTP GET method
     * @param string $url
     * @return string/bool
     */
    public function getRequest($url) {
        $this->_url = $url;

        if (!empty($this->_queries)) {
            $queryStr = http_build_query($this->_queries);
            if (strpos('?', $url) === false) {
                $this->_url .= '?' . $queryStr;
            } else {
                $this->_url .= '&' . $queryStr;
            }
        }

        return $this->doRequest() ? $this->_responseBody : false;
    }

    /**
     * Sent the request use HTTP POST method
     * @param string $url
     * @return string/bool
     */
    public function postRequest($url) {
        $this->_url = $url;
        curl_setopt($this->_curl, CURLOPT_POST, true);
        if (!empty($this->_queries)) {
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $this->_queries);
        }

        return $this->doRequest() ? $this->_responseBody : false;
    }

    /**
     * Set the cookie
     * @param mixed $cookies the cookies can be string like "foo:bar; foo2:bar2" or a array('foo' => 'bar', 'foo2' => 'bar2');
     */
    public function setCookies($cookies) {
        if (is_array($cookies)) {
            foreach ($cookies as $key => $value) {
                $this->setCookie($key, $value);
            }
        }

        if (is_string($cookies)) {
            $cookieStrs = explode(';', $cookies);
            foreach ($cookieStrs as $cookie) {
                $pair = explode('=', $cookie);
                $this->setCookie(trim($pair[0]), $pair[1]);
            }
        }
    }

    /**
     * Set single cookie entry
     * @param string $key
     * @param string $value
     */
    public function setCookie($key, $value) {
        $this->_cookies[$key] = $value;
    }

    /**
     * Clear all the cookies
     * @return void
     */
    public function clearCookies() {
        $this->_cookies = array();
    }

    /**
     * Set multiple header entries
     * @param array $headers the header info array, like array('Content-type' => 'text/plain', 'Content-length' => 100)
     */
    public function setHeaders($headers) {
        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $this->setHeader($key, $value);
            }
        }
    }

    /**
     * Set the header value
     * @param string $header
     * @param string $value
     */
    public function setHeader($header, $value) {
        $this->_headers[$header] = $value;
    }

    /**
     * Set the query parameters by array. 
     * The key of the array will be the parameter name
     * and the value of it will be the query value
     * @param mixed $queries query string or array 
     */
    public function setQueries($queries) {
        if (is_array($queries)) {
            foreach ($queries as $key => $value) {
                $this->setQuery($key, $value);
            }
        }

        if (is_string($queries)) {
            $queryStrs = explode('&', $queries);
            foreach ($queryStrs as $query) {
                $pair = explode('=', $query, 2);
                $this->setQuery(trim($pair[0]), $pair[1]);
            }
        }
    }

    /**
     * Set the query parameter of the request,
     * both GET and POST 
     * @param string $key
     * @param string $value
     */
    public function setQuery($key, $value) {
        $this->_queries[$key] = $value;
    }

    /**
     * Set the cURL option
     * @param int $option this should be the constant pre-defined by
     *                    the cURL extension
     * @param mixed $value the value of the option you want to set 
     */
    public function setCurlOption($option, $value) {
        curl_setopt($this->_curl, $option, $value);
    }

    /**
     * Static interface, send a GET request and return the result
     * @param string $url
     * @param array $queries
     * @param array $cookies
     * @param array $headers
     * @return string/false
     */
    public static function get($url, $queries = array(), $cookies = array(), $headers = array()) {
        $client = self::getInstance();
        $client->setCookies($cookies);
        $client->setQueries($queries);
        $client->setHeaders($headers);
        $ret = $client->getRequest($url);

        if ($client->getErrorCode() === CURLE_OK) {
            return $ret;
        }

        return false;
    }

    /**
     * Static interface, send a POST request and return the result
     * @param string $url
     * @param array $queries
     * @param array $cookies
     * @param array $headers
     * @return string/false
     */
    public static function post($url, $queries = array(), $cookies = array(), $headers = array()) {
        $client = self::getInstance();
        $client->setCookies($cookies);
        $client->setQueries($queries);
        $client->setHeaders($headers);
        $ret = $client->postRequest($url);

        if ($client->getErrorCode() === CURLE_OK) {
            return $ret;
        }

        return false;
    }

    /**
     * Static interface, send a GET request and return the result
     * @param string $url
     * @param array $queries
     * @param array $cookies
     * @param array $headers
     * @return array/false
     */
    public static function getJson($url, $queries = array(), $cookies = array(), $headers = array()) {
        $ret = self::get($url, $queries, $cookies, $headers);
        if (false !== $ret) {
            return json_decode($ret, true);
        }

        return false;
    }

    /**
     * Static interface, send a POST request and return the result
     * @param string $url
     * @param array $queries
     * @param array $cookies
     * @param array $headers
     * @return array/false
     */
    public static function postJson($url, $queries = array(), $cookies = array(), $headers = array()) {
        $ret = self::post($url, $queries, $cookies, $headers);
        if (false !== $ret) {
            return json_decode($ret, true);
        }

        return false;
    }

    /**
     * A singleton pattern, factory method
     * @return CurlHttpClient when success return CurlHttpClient instance, of FALSE on error;
     */
    public static function getInstance() {
        if (self::$_curlHttpClient == null) {
            try {
                self::$_curlHttpClient = new CurlHttpClient();
            } catch (\Exception $e) {
                return false;
            }
        }

        return self::$_curlHttpClient;
    }

    /**
     * Get the response body of http request
     * @return string
     */
    public function getResponseBody() {
        return $this->_responseBody;
    }

    /**
     * Get the response header of the http request
     * @return string
     */
    public function getRawResponseHeader() {
        return $this->_responseHeaderStr;
    }

    /**
     * Get the response headers of the http request
     * @return array
     */
    public function getResponseHeaders() {
        if ($this->_responseHeaderStr == '') {
            return array();
        }
        if (empty($this->_responseHeaders)) {
            $this->parseResponseString();
        }
        return $this->_responseHeaders;
    }

    protected function parseResponseString() {
        $lines = explode("\n", $this->_responseHeaderStr);
        foreach ($lines as $l) {
            $l = trim($l);
            if (empty($l)) {
                continue;
            }
            if (strpos($l, ": ")) {
                $pairs = explode(": ", $l, 2);
                $this->_responseHeaders[$pairs[0]] = trim($pairs[1]);
            } else {
                $matches = array();
                if (preg_match('/^HTTP\/(\d\.\d)\s+(\d+)\s+(\w+)/i', $l, $matches)) {
                    $this->_responseHeaders['HTTP_VERSION'] = $matches[1];
                    $this->_responseHeaders['HTTP_STATUS'] = $matches[2];
                    $this->_responseHeaders['HTTP_ERROR'] = $matches[3];
                }
            }
        }

        if (isset($this->_responseHeaders['Content-Type'])) {
            $contentInfo = explode('; ', $this->_responseHeaders['Content-Type']);
            $this->_responseHeaders['MIME_TYPE'] = trim($contentInfo[0]);
            foreach ($contentInfo as $info) {
                if (strpos($info, 'charset') !== false) {
                    $charInfo = explode('=', $info);
                    $this->_responseHeaders['CHARSET'] = strtoupper(trim($charInfo[1]));
                }
            }
        }
    }
//<editor-fold defaultstate="collapsed" desc="Getter">
    /**
     * The error code of the curl
     * @return int
     */
    public function getErrorCode() {
        return $this->_errorCode;
    }

    /**
     * The error msg of the curl
     * @return string
     */
    public function getErrorMsg() {
        return $this->_errorMsg;
    }
//</editor-fold>
}
