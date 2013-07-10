<?php

/**
 * Description of CurlHttpClientComponent
 *
 * @author Charles Tang<charlestang@foxmail.com>
 */
class CurlHttpClientComponent extends CApplicationComponent {

    private $_curlHttpClient = null;

    public function init() {
        parent::init();

        $this->_curlHttpClient = CurlHttpClient::getInstance();
    }

    public function __call($name, $parameters) {
        if (method_exists($this->_curlHttpClient, $name)) {
            return call_user_func_array(array($this->_curlHttpClient, $name), $parameters);
        }
        return parent::__call($name, $parameters);
    }

    public function setHttpProxy($value) {
        $this->_curlHttpClient->setCurlOption(CURLOPT_PROXY, $value);
    }

}
