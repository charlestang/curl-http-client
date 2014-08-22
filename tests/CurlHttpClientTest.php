<?php
$dir = dirname(__FILE__);
require $dir . '/../CurlHttpClient.php';
/**
 * The unit test of the curl http client.
 *
 * @author Charles Tang<charlestang@foxmail.com> 
 */
class CurlHttpClientTest extends PHPUnit_Framework_TestCase {

    /**
     * Test if the curl http client can be create normally.
     */
    public function testInit() {
        $curlHttpClient = new CurlHttpClient();
        $this->assertNotNull($curlHttpClient, 'Initialize failed!');
        $this->assertAttributeInternalType('resource', '_curl', $curlHttpClient, 'The curl client is not initialized correctly.');
        return $curlHttpClient;
    }

    /**
     * Test the setCookie method
     * @depends testInit
     */
    public function testSetCookie($curlHttpClient) {
        $this->assertAttributeEmpty('_cookies', $curlHttpClient);
        $curlHttpClient->setCookie('key', 'value');
        $this->assertAttributeEquals(array('key' => 'value'), '_cookies', $curlHttpClient);
        return $curlHttpClient;
    }

    /**
     * @depends testSetCookie
     */
    public function testClearCookies($curlHttpClient) {
        $this->assertAttributeNotEmpty('_cookies', $curlHttpClient);
        $curlHttpClient->clearCookies();
        $this->assertAttributeEmpty('_cookies', $curlHttpClient); 
        return $curlHttpClient;
    }

    /**
     * @depends testInit 
     */
    public function testSetCookies($curlHttpClient) {
        $curlHttpClient->setCookies('key1=value1; key2=value2');
        $this->assertAttributeEquals(array('key1'=>'value1', 'key2'=>'value2'), '_cookies', $curlHttpClient);
    }

    /**
     * @param CurlHttpClient $curlHttpClient
     * @depends testInit 
     */
    public function testSetQueries($curlHttpClient) {
        $this->assertAttributeEmpty('_queries', $curlHttpClient);
        $curlHttpClient->setQueries(array('a' => 'b', 'c' => 'd'));
        $curlHttpClient->setQueries('key1=value1&key2=value2');
        $this->assertAttributeEquals(array('a'=>'b', 'c'=>'d', 'key1'=>'value1', 'key2'=>'value2'), '_queries', $curlHttpClient);
    }

    /**
     * @param CurlHttpClient $curlHttpClient
     * @depends testInit
     */
    public function testGetRequest($curlHttpClient) {
        $curlHttpClient->getRequest("http://www.qq.com");
        $this->assertEquals(0, $curlHttpClient->getErrorCode());
        $responseHeaders = $curlHttpClient->getResponseHeaders();
        $this->assertEquals(200, $responseHeaders['HTTP_STATUS']);
        $this->assertEquals('UTF-8', $responseHeaders['CHARSET']);
    }
}
