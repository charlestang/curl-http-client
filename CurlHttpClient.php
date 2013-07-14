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
     * @var float the version of the class. 
     */
    public static $version = 1.0;

    /**
     * @var CurlHttpClient 
     */
    private static $_curlHttpClient = null;

    /**
     * @var array default options should set to cURL 
     */
    private static $_defaultOptions = array(
        CURLOPT_RETURNTRANSFER => true, //return the result
        CURLOPT_SSL_VERIFYPEER => false, //ignore SSL
        CURLOPT_TIMEOUT        => 8, //default timeout 
        CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1) Gecko/20100101 Firefox/22.0',
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
     * @var string the request uri 
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
    private $_responseHeader = array();

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
    private function init() {
        $this->_options = array();
        $this->_url = '';
        $this->_headers = array();
        $this->_cookies = array();
        $this->_queries = array();
        $this->_errorCode = 0;
        $this->_errorMsg = '';
        $this->_responseHeaderStr = '';
        $this->_responseBody = '';
        $this->_responseHeader = array();
        $this->_curl = curl_init();
        if ($this->_curl === false) {
            throw new Exception("cURL init failed.");
        }
        if (!curl_setopt_array($this->_curl, self::$_defaultOptions)) {
            throw new Exception("cURL default options set failed.");
        }
    }

    /**
     * Re-create the curl resource, and initialize all the member variables
     * @throws Exception
     */
    public function reset() {
        if ($this->_curl != null && is_resource($this->_curl)) {
            curl_close($this->_curl);
        }
        $this->_curl = null;
        $this->init();
    }

    protected function doRequest() {
        curl_setopt_array($this->_curl, $this->_options);

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
            $response = explode("\r\n", $ret, 2);
            $this->_responseHeaderStr = $response[0];
            $this->_responseBody = $response[1];
        } else {
            $this->_errorCode = curl_errno($this->_curl);
            $this->_errorMsg = curl_error($this->_curl);
        }

        return $this->_errorCode === 0;
    }

    public function getRequest($url) {
        $this->_url = $url;
        $queryStr = '';
        if (!empty($this->_queries)) {
            $queryStr = http_build_query($this->_queries);
        }

        if (strpos('?', $url) === false) {
            $this->_url .= '?' . $queryStr;
        } else {
            $this->_url .= '&' . $queryStr;
        }

        if ($this->doRequest()) {
            return $this->_responseBody;
        }
    }

    public function postRequest($url) {
        $this->_url = $url;
        curl_setopt($this->_curl, CURLOPT_POST, true);
        if (!empty($this->_queries)) {
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $this->_queries);
        }

        if ($this->doRequest()) {
            return $this->_responseBody;
        }
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

    public function setQuery($key, $value) {
        $this->_queries[$key] = $value;
    }

    public function setCurlOption($option, $value) {
        curl_setopt($this->_curl, $option, $value);
    }

    public function getErrorCode() {
        return $this->_errorCode;
    }

    public function getErrorMsg() {
        return $this->_errorMsg;
    }

    /**
     * Static interface, send a GET request and return the result
     * @param string $url
     * @param array $query
     * @param array $cookies
     * @param array $header
     * @return string/false
     */
    public static function get($url, $query = array(), $cookies = array(), $header = array()) {
        $client = self::getInstance();
        $client->setCookies($cookies);
        $client->setQueries($query);
        $client->setHeaders($header);
        $ret = $client->getRequest($url);

        if ($client->getErrorCode() === CURLE_OK) {
            return $ret;
        }

        return false;
    }

    /**
     * Static interface, send a POST request and return the result
     * @param string $url
     * @param array $query
     * @param array $cookies
     * @param array $header
     * @return string/false
     */
    public static function post($url, $query = array(), $cookies = array(), $header = array()) {
        $client = self::getInstance();
        $client->setCookies($cookies);
        $client->setQueries($query);
        $client->setHeaders($header);
        $ret = $client->postRequest($url);

        if ($client->getErrorCode() === CURLE_OK) {
            return $ret;
        }

        return false;
    }

    /**
     * Static interface, send a GET request and return the result
     * @param string $url
     * @param array $query
     * @param array $cookies
     * @param array $header
     * @return array/false
     */
    public static function getJson($url, $query = array(), $cookies = array(), $header = array()) {
        $ret = self::get($url, $query, $cookies, $header);
        if (false !== $ret) {
            return json_decode($ret, true);
        }

        return false;
    }

    /**
     * Static interface, send a POST request and return the result
     * @param string $url
     * @param array $query
     * @param array $cookies
     * @param array $header
     * @return array/false
     */
    public static function postJson($url, $query = array(), $cookies = array(), $header = array()) {
        $ret = self::post($url, $query, $cookies, $header);
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

}
