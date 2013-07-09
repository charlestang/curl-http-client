<?php

/**
 * This is a wrapper class of cURL extension of php
 * @author Charles Tang<charlestang@foxmail.com>
 */
class CurlHttpClient {

    public static $version = 1.0;
    private static $_curlHttpClient = null;
    private static $_defaultOptions = array(
        CURLOPT_RETURNTRANSFER => true,  //return the result
        CURLOPT_SSL_VERIFYPEER => false, //ignore SSL
        CURLOPT_TIMEOUT        => 8,     //default timeout 
        CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1) Gecko/20100101 Firefox/22.0',
    );

    /**
     * cURL instance
     * @var resource 
     */
    private $_curl = null;

    /**
     * Constructor
     * @throws Exception
     */
    public function __construct() {
        $this->_curl = curl_init();
        if ($this->_curl === false) {
            throw new Exception("cURL init failed.");
        }
        if (!curl_setopt_array($this->_curl, self::$_defaultOptions)) {
            throw new Exception("cURL default options set failed.");
        }
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
     * @return CurlHttpClient
     */
    public static function getInstance() {
        if (self::$_curlHttpClient == null) {
            self::$_curlHttpClient = new CurlHttpClient();
        }

        return self::$_curlHttpClient;
    }

}
