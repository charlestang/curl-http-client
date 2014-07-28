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
        $curl = new CurlHttpClient();
        $this->assertAttributeInternalType('resource', '_curl', $curl, 'The curl client is not initialized correctly.');
    }
}
