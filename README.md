# evaneos/events


### Build status

[![Build Status](https://travis-ci.org/aztech-dev/events.png?branch=master)](https://travis-ci.org/aztech-dev/events)
[![Code Coverage](https://scrutinizer-ci.com/g/aztech-dev/events/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/aztech-dev/events/?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/aztech-dev/events/badges/quality-score.png?s=668e4df5ba163c804504257d4a026a0a549f220a)](https://scrutinizer-ci.com/g/aztech-dev/events/)
[![Dependency Status](https://www.versioneye.com/user/projects/53b92a84609ff04f7f000003/badge.svg)](https://www.versioneye.com/user/projects/53b92a84609ff04f7f000003)

### Stability

[![Latest Stable Version](https://poser.pugx.org/evaneos/events/v/stable.png)](https://packagist.org/packages/evaneos/events)
[![Latest Unstable Version](https://poser.pugx.org/evaneos/events/v/unstable.png)](https://packagist.org/packages/evaneos/events)

## Simple event lib 

### Installation

#### Via Composer

Composer is the officially supported way of installing evaneos/events . Don't know Composer yet ? [Read more about it](https://getcomposer.org/doc/00-intro.md).


`$ composer require "evaneos/events":"dev-master"`

### Autoloading

Add the following code to your bootstrap file :

```
require_once 'vendor/autoload.php';
```

### Concepts

In *evaneos/events*, the event dispatch process is split in two separate processes, **publishing** (or emitting events), and **dispatching** (submitting the event to the 
relevant **subscribers**). This pattern simplifies "out-of-process" event processing by allowing asynchronous transports to be used.

If you want to create and publish events, you will need to use a **publisher**. Currently, the library provides native support for publishing events to AMQP-compatible message queues and synchronous event publishing.

If you want to consume published events, you will need to use a **processor**. A processor is responsible for receiving events via whatever transport it uses. Currently, the library provides native support for consuming AMQP-compatible message queues and synchronous event consumption. The library provides hooks to which you can bind **subscribers**, which are simple event handlers.

### Usage

For simplicity, there are factories available to create publishers and dispatchers.

#### Simple event publish/subscribe

##### Publishing

```php

$factory = \Evaneos\Events\Factory::createSimpleFactory();
$publisher = $factory->createPublisher();
$event = new \Evaneos\Events\SimpleEvent('category', array('property' => 'value'));

$publisher->publish($event);
``̀

##### Consuming

$factory = \Evaneos\Events\Factory::createSimpleFactory();
$consumer = $factory->createConsumer($options);

// Subscribe to all events using a wildcard filter
$consumer->on('*', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

while (true) {
    $consumer->consumeNext();
}


#### Event publish/subscriber via an AMQP broker

##### Publishing

```php
$options = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'user' => 'username',
    'pass' => 'password',
    'vhost' => '/',
    'exchange' => 'exchangeName'
);

$factory = \Evaneos\Events\Factory::createAmqpFactory();
$publisher = $factory->createPublisher($options);
$event = new \Evaneos\Events\SimpleEvent('category', array('property' => 'value'));

$publisher->publish($event);
```

##### Consuming

```php
$options = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'user' => 'username',
    'pass' => 'password',
    'vhost' => '/',
    'event-queue' => 'queueName'
);

$factory = \Evaneos\Events\Factory::createAmqpFactory();
$consumer = $factory->createConsumer($options);

// Subscribe to all events using a wildcard filter
$consumer->on('*', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

while (true) {
    $consumer->consumeNext();
}

```

The `on` method accepts as a first argument a category filter expression. The internal matching engine evaluates matches on a first-match-wins basis, and accepts wildcards.

##### Event matching "truth table"

To get check the latest truth table of event matching, please refer to the source of `Evaneos\Events\Tests\Unit\CategoryMatchTruthTable`.