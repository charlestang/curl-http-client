# curl-http-client


CurlHttpClient is a object oriented wrapper of PHP cURL extension. It includes
a class CurlHttpClient and a set of handy apis, which are a set of static methods included in the class. Besides, a Yii framework component wrapper is provided with it.

## API List

### Static functions

1. [CurlHttpClient::getInstance()](#curlhttpclientgetinstance)
2. [CurlHttpClient::get()](#curlhttpclientget)
3. [CurlHttpClient::getJson()](#curlhttpclientgetjosn)
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
