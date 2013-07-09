<?php

/**
 * This is a wrapper class of cURL extension of php
 * @author Charles Tang<charlestang@foxmail.com>
 */
class CurlHttpClient {

    public static $version = 1.0;
    private static $_curlHttpClient = null;
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
     * @var array the headers container 
     */
    private $_headers = array();

    /**
     * Constructor
     * @throws Exception
     */
    public function __construct() {
        $this->init();
    }

    public function init() {
        $this->_options = array();
        $this->_headers = array();
        $this->_curl = curl_init();
        if ($this->_curl === false) {
            throw new Exception("cURL init failed.");
        }
        if (!curl_setopt_array($this->_curl, self::$_defaultOptions)) {
            throw new Exception("cURL default options set failed.");
        }
    }

    public function reset() {
        if ($this->_curl != null && is_resource($this->_curl)) {
            curl_close($this->_curl);
        }
        $this->_curl = null;
        $this->init();
    }

    /**
     * Set the cookie
     * @param mixed $cookies the cookies can be string like "foo:bar; foo2:bar2" or a array('foo' => 'bar', 'foo2' => 'bar2');
     */
    public function setCookies($cookies) {
        $cookiesStr = $cookies;
        if (is_array($cookies)) {
            $cookiesStr = '';
            foreach ($cookies as $key => $value) {
                $cookiesStr .= "$key=$value; "; //joined them by a simecolon followed by a space
            }
            $cookiesStr = trim($cookiesStr, '; ');
        }

        $this->_options[CURLOPT_COOKIE] = $cookiesStr;
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
     * Static interface, send a GET request and return the result
     * @param string $url
     * @param array $query
     * @param array $cookies
     * @param array $header
     * @return string/false
     */
    public static function get($url, $query = array(), $cookies = array(), $header = array()) {
        
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
