<?php
$dir = dirname(__FILE__);
require $dir . '/../CurlHttpClient.php';
/**
 * The unit test of the curl http client.
 *
 * @author Charles Tang<charlestang@foxmail.com> 
 */
class CurlHttpClientTest extends PHPUnit_Framework_TestCase {

    public $curlHttpClient = null;

    /**
     * Test if the curl http client can be create normally.
     */
    public function testInit() {
        $this->curlHttpClient = new CurlHttpClient();
        $this->assertNotNull($this->curlHttpClient, 'Initialize failed!');
        $this->assertAttributeInternalType('resource', '_curl', $this->curlHttpClient, 'The curl client is not initialized correctly.');
    }

    /**
     * Test the setCookie method
     * @depends testInit
     */
    public function testSetCookie() {
        $this->assertAttributeEmpty('_cookies', $this->curlHttpClient);
        $this->curlHttpClient->setCookie('key', 'value');
        $this->assertAttributeEquals(array('key' => 'value'), '_cookies', $this->curlHttpClient);
    }

    /**
     * @depends testSetCookie
     */
    public function testClearCookies() {
        $this->assertAttributeNotEmpty('_cookies', $this->curlHttpClient);
        $this->curlHttpClient->clearCookies();
        $this->assertAttributeEmpty('_cookies', $this->curlHttpClient); 
    }

    /**
     * @depends testClearCookies
     */
    public function testSetCookies() {
        $this->curlHttpClient->setCookies('key1=value1; key2=value2');
        $this->assertAttributeEquals(array('key1'=>'value1', 'key2'=>'value2'), '_cookies', $this->curlHttpClient);
    }
}
