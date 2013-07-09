<?php

/**
 * This is a wrapper class of cURL extension of php
 */
class CurlHttpClient {

    public static $version = 1.0;
    private static $_curlHttpClient = null;

    public function __construct() {
        
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
