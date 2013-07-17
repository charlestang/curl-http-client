# curl-http-client


CurlHttpClient is a object oriented wrapper of PHP cURL extension. It includes
a class CurlHttpClient and a set of handy apis, which are a set of static methods included in the class. Besides, a Yii framework component wrapper is provided with it.

## API List

### Static functions

1. [CurlHttpClient::getInstance()](#curlhttpclientgetinstance)
2. [CurlHttpClient::get()](#curlhttpclientget)
3. [CurlHttpClient::getJson()](#curlhttpclientgetjson)
4. [CurlHttpClient::post()](#curlhttpclientpost)
5. [CurlHttpClient::postJson()](#curlhttpclientpostjson)

### Properties

1. $version

### Methods

1. [__construct()]
2. [reset()]
3. [getRequest()]
4. [postRequest()]
5. [setCookie()]
6. [setCookies()]
7. [setHeader()]
8. [setHeaders()]
9. [setQuery()]
10. [setQueries()]
11. [setCurlOption()]
12. [getErrorCode()]
13. [getErrorMsg()]
14. [getRawHeader()]
15. [getHeaders()]

## Documents

### CurlHttpClient::getInstance()

#### Description

A singleton factory to generate a single CurlHttpClient object.

#### Parameters

void

#### Return value

This function will return a CurlHttpClient object when success, or `false` on failure.

### CurlHttpClient::get()

#### Description

Simple and handy GET request

#### Parameters

* $url --- request url
* $queries --- request parameters
* $cookies --- cookies from last request
* $headers --- headers should include

#### Return value

The request result will be returned in a string.

### CurlHttpClient::getJson()

#### Description

Simple and handy GET request, but the result is JSON

#### Parameters

* $url --- request url
* $queries --- request parameters
* $cookies --- cookies from last request
* $headers --- headers should include

#### Return value

The request result will be treated as JSON string and before returned, the `json_decode` function will be called.

### CurlHttpClient::post()

#### Description

Simple and handy POST request

#### Parameters

* $url --- request url
* $queries --- request parameters
* $cookies --- cookies from last request
* $headers --- headers should include

#### Return value

The result string of the POST request.

### CurlHttpClient::postJson()

#### Description

Simple and handy POST request, but the result is JSON

#### Parameters

* $url --- request url
* $queries --- request parameters
* $cookies --- cookies from last request
* $headers --- headers should include

#### Return value

The request result will be treated as JSON string and before returned, the `json_decode` function will be called.
