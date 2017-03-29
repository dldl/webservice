dLdLWebService
==============

This PHP library allows you to follow a normalized way to connect to your web services, with support for logs and cache following
PSR-3 and PSR-6.

[![Build Status](https://travis-ci.org/dldl/webservice.svg?branch=master)](https://travis-ci.org/dldl/webservice)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d5e04165-7382-4cfa-aa34-8860f96af5ab/mini.png)](https://insight.sensiolabs.com/projects/d5e04165-7382-4cfa-aa34-8860f96af5ab)
[![Latest Stable Version](https://poser.pugx.org/dldl/webservice/v/stable)](https://packagist.org/packages/dldl/webservice)
![Licence](https://img.shields.io/github/license/dldl/webservice.svg)

Installation
------------

Install it using Composer:

```sh
composer require dldl/webservice
```

Usage
-----

Here is a simple example to see how this library can be used:

```php
<?php

namespace MyApp\WebService;

use dLdL\WebService\WebServiceInterface;
use dLdL\WebService\ConnectorInterface;
use dLdL\WebService\ParserInterface;
use dLdL\WebService\Exception\WebServiceException;
use dLdL\WebService\Http\Request;

class FacebookWebService implements WebServiceInterface
{
    private $connector;
    private $parser;
    private $host;

    public function __construct(ConnectorInterface $connector, ParserInterface $parser, $host)
    {
        $this->connector = $connector;
        $this->parser = $parser;
        $this->host = $host;
    }

    public function getConnector()
    {
        return $this->connector;
    }
    
    public function getPosts($facebookUsername)
    {
        try {
            $this->getConnector()->connect($this->host);
        } catch (WebServiceException $e) {
            return [];
        }
        
        $request = new Request($facebookUsername . '/feed');
        $this->getConnector()->getCache()->getConfig()->add($request, 60*60*24);
        
        try {
            $postData = $this->getConnector()->sendRequest($request);
        } catch (WebServiceException $e) {
            return [];
        }
        
        $this->getConnector()->disconnect();
        
        return $this->parser->parse($postData);
    }
}
```

Of course, you may perform specific actions in the catch blocks.

The main idea is to split the web service requests into three parts:

 - *Connector*, in charge to connect to the web service and to grab and/or send raw data from a predefined request
 - *Parser*, in charge to transform this raw data to business objects
 - *WebService*, in charge to check business conditions calling services but also to delegate the call to *connectors*
   and to *parsers*
 
This allows to separate the way data is retrieve from the way data is aim to be used. It can be easy to cache, log,
add extensions such as proxies and change the type of connector at any moment (for example, to move from a SOAP to a
REST web service).

Connectors must implement the `ConnectorInterface`. The easiest way is to extend the `AbstractConnector` class which provides
logs and cache out of the box. Connectors can use any technology such as `cURL`, `Guzzle`, `Soap` or any specific library
but must be independent to the data it handles.

Contribution
------------

Every functionality must be tested and documented. To contribute:

 1. Clone the repository
 2. Install dependencies, using composer: `composer install`
 3. Run tests, using PHPUnit: `./vendor/bin/phpunit`
