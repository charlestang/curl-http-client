# curl-http-client

![Travis CI Status](https://travis-ci.org/charlestang/curl-http-client.svg?branch=master "Status Picture")

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

1. [$version](#version)

### Methods

1. [__construct()](#__construct)
2. [reset()](#reset)
3. [getRequest()](#getrequest)
4. [postRequest()](#postrequest)
5. [setCookie()](#setcookie)
6. [setCookies()](#setcookies)
7. [setHeader()](#setheader)
8. [setHeaders()](#setheaders)
9. [setQuery()](#setquery)
10. [setQueries()](#setqueries)
11. [setCurlOption()](#setcurloption)
12. [getErrorCode()](#geterrorcode)
13. [getErrorMsg()](#geterrormsg)
14. [getRawResponseHeader()](#getrawresponseheader)
15. [getResponseHeaders()](#getresponseheaders)
16. [getResponseBody()](#getresponsebody)

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

### $version

This static variable is set to the version of the class.

### __construct()

The constructor of the  CurlHttpClient object.

#### Parameters

void

#### Return value

void

### reset()

This method will reset the cURL resource and clear the last request information.

#### Parameters

void

#### Return value

void

### getRequest()

Send the request with HTTP GET method.

#### Parameters

 * $url
 
#### Return value

The response body of the requst.

### postRequest()

Send the request with HTTP POST method.

#### Parameters

 * $url

#### Return value

The response body of the requst.

### setCookie()

Set the request cookie.

#### Parameters

 * $key string
 * $value string

#### Return value

void

### setCookies()

Set the request cookies by array or cookie string.

#### Parameters

 * $cookies mixed this argument could be a string like(key1=val1; key2=val2) or
   a associative array of PHP.

#### Return value

void

### setHeader()

Set the request header with key and value.

#### Parameters

 * $key string
 * $value string

#### Return value

void


### setHeaders()

Set headers with associative array.

#### Parameters

 * $headers array

#### Return value

void

### setQuery()

Set the quest parameter.

#### Parameters

 * $key string
 * $value string

#### Return value

void

### setQueries()

Set multiple query parameters with query string or associative array.

#### Parameters

 * $queries mixed string/array

#### Return value

### setCurlOption()

Set the cURL option to the cURL resource handler in the object.

#### Parameters

 * $option int this should be the cURL predefined constant.
 * $value mixed

#### Return value

void

### getErrorCode()

Get the error code the cURL returned after the request.

#### Parameters

void

#### Return value

The error code of the cURL.

### getErrorMsg()

Get the string error message information of cURL.

#### Parameters

void

#### Return value

The error message cURL generated.

### getRawResponseHeader()

Get the response header of the request. This is in raw, that means a single 
string, each response header in the string is separated by "\r\n"

#### Parameters

void

#### Return value

The response header of the request.

### getResponseHeaders()

Get the header string parsed result. The header string will be parse to an 
associative array, the header name will be the key and the header value will be 
the value.

#### Parameters

void

#### Return value

array

### getResponseBody()

#### Parameters

void

#### Return value

string