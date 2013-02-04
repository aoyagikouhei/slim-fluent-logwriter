slim-fluent-logwriter
=====================

A Fluentd log writter for Slim Framework. slim-fluent-logwriter require Slim 2.0+.

Installation
------------

Add `"aoyagikouhei/slim-fluent-logwriter"` to your `composer.json` file:

``` json
{
  "require": {
    "slim/slim": "2.2.*",
    "aoyagikouhei/slim-fluent-logwriter": "0.0.*"
  }
}
```

And install using composer:

``` bash
$ php composer.phar install
```

Configuration
-------------

``` php
$writer = new \Slim\FluentLogwriter(array('host' => 'localhost'));
$app = new \Slim\Slim(array(
  'log.writer' => $writer,
));
```

Full options specified

``` php
$callable = function($logger, $entity, $error) {
    throw $error;
};
$writer = new \Slim\FluentLogwriter(array(
  'host' => 'localhost',
  'port' => '24224',
  'tag' => 'mongo.systemlog',
  'level' => \Slim\Log::INFO,
  'tag_with_date' => 'Ym',
  'error_handler' => $callable
));
$writer->addFluent(array(
  'host' => 'localhost',
  'port' => '24224',
  'tag' => 'mail.systemlog',
  'level' => \Slim\Log::WARN
));
$app = new \Slim\Slim(array(
  'log.writer' => $writer,
));
```

First Fluent Settings is to mongodb log.

Second Fluent Setting is to mail.

You can add more Fluent settings.

Options
-------
host : host name, default 'localhost'

port : port, default '24224'

tag : fluent tag name, default 'systemlog'

level : write log level, default \Slim\Log::DEBUG

tag_with_date : postfix date for tag by DateTime format, default none

error_handler : when call error, default stderr


Copyright
---------

Copyright (c) 2013 [Kouhei Aoyagi](http://aoyagikouhei.blog8.fc2.com/).