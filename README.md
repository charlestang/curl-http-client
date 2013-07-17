# curl-http-client


CurlHttpClient is a object oriented wrapper of PHP cURL extension. It includes
a class CurlHttpClient and a set of handy apis, which are a set of static methods included in the class. Besides, a Yii framework component wrapper is provided with it.

## API List

### Static functions

1. [CurlHttpClient::getInstance()](#curlhttpclientgetinstance)
2. [CurlHttpClient::get()][static_get]
3. [CurlHttpClient::getJson()][static_getJson]
4. [CurlHttpClient::post()][static_post]
5. [CurlHttpClient::postJson()][static_postJson]

### Properties

1. $version

### Methods

1. [__construct()][method_construct]
2. [reset()][method_reset]
3. [getRequest()][method_getRequest]
4. [postRequest()][method_postRequest]
5. [setCookie()][method_setCookie]
6. [setCookies()][method_setCookies]
7. [setHeader()][method_setHeader]
8. [setHeaders()][method_setHeaders]
9. [setQuery()][method_setQuery]
10. [setQueries()][method_setQueries]
11. [setCurlOption()][method_setCurlOption]
12. [getErrorCode()][method_getErrorCode]
13. [getErrorMsg()][method_getErrorMsg]
14. [getRawHeader()][method_getRawHeader]
15. [getHeaders()][method_getHeaders]

## Documents

### CurlHttpClient::getInstance()

A singleton factory to generate a single CurlHttpClient object.

#### Arguments

void

#### Return

This function will return a CurlHttpClient object when success, or `false` on failure.

[static_get]: "Simple and handy GET request"
### CurlHttpClient::get()

#### Arguments

#### Return


[static_getJson]: "Simple and handy GET request, but the result is JSON"
### CurlHttpClient::getJson()

#### Arguments

#### Return

[static_post]: "Simple and handy POST request"
### CurlHttpClient::post()

#### Arguments

#### Return

[static_postJson]: "Simple and handy POST request, but the result is JSON"
### CurlHttpClient::postJson()

#### Arguments

#### Return
