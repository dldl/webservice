dLdLWebService
==============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d5e04165-7382-4cfa-aa34-8860f96af5ab/mini.png)](https://insight.sensiolabs.com/projects/d5e04165-7382-4cfa-aa34-8860f96af5ab)
[![Latest Stable Version](https://poser.pugx.org/dldl/webservice/v/stable)](https://packagist.org/packages/dldl/webservice)
[![Licence](https://img.shields.io/github/license/dldl/webservice.svg)

**This library is still under heavy development.**

This PHP library allows you to follow a normalized way to connect to your WebServices, with logs and cache following
PSR-3 and PSR-6.

Installation
------------

Install it with composer:

```sh
composer require dldl/webservice
```

Usage
-----

Here is a simple commented example to see how it can be used:

```php
<?php

namespace MyApp\WebService;

use dLdL\WebService\WebServiceInterface;
use dLdL\WebService\AdapterInterface;
use dLdL\WebService\ParserInterface;
use dLdL\WebService\Exception\WebServiceException;
use dLdL\WebService\Http\Request;

class FacebookWebService implements WebServiceInterface
{
    private $adapter;
    private $host;
    private $parser;

    public function __construct(AdapterInterface $adapter, ParserInterface $parser, $host)
    {
        $this->adapter = $adapter;
        $this->host = $host;
        $this->parser = $parser;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }
    
    public function getPosts($facebookUsername)
    {
        try {
            $this->getAdapter()->connect($this->host);
        } catch (WebServiceException $e) {
            return [];
        }
        
        $request = new Request($facebookUsername . '/feed');
        $this->getAdapter()->getCache()->getConfig()->add($request, 60*60*24);
        
        try {
            $postData = $this->getAdapter()->sendRequest($request);
        } catch (WebServiceException $e) {
            return [];
        }
        
        $this->getAdapter()->disconnect();
        
        return $this->parser->parse($postData);
    }
}
```

The main idea is to split the WebService requests into three parts:

 - *Adapters*, in charge to connect to the WebService and to grab and/or send raw data from a predefined request
 - *Parsers*, in charge to transform this raw data to business objects
 - *WebServices*, in charge to check business conditions calling services but also to delegate the call to *adapters*
   and to call *parsers*
 
This allow to separate the way data is retrieve from the way data is aim to be used. It can be easy to cache, log,
add extensions such as proxies and change the type of adapter at any moment (for example, to move from a SOAP to a
REST WebService).

Adapters must implement the `AdapterInterface`. The easiest way is to extend the `AbstractAdapter` class. Adapters
can use any technology such as `cURL`, `Guzzle`, `Soap` or any specific library but must be independent to the data
it handles.

Contribution
------------

Every functionality must be tested and documented. To contribute:

 1. Clone the repository
 2. Install dependencies, using composer: `composer install`
 3. Run tests, using PHPUnit: `./vendor/bin/phpunit`
