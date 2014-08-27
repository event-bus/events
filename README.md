# aztech/events

## Build status

[![Build Status](https://travis-ci.org/aztech-dev/events.png?branch=master)](https://travis-ci.org/aztech-dev/events)
[![Code Coverage](https://scrutinizer-ci.com/g/aztech-dev/events/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/aztech-dev/events/?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/aztech-dev/events/badges/quality-score.png?s=668e4df5ba163c804504257d4a026a0a549f220a)](https://scrutinizer-ci.com/g/aztech-dev/events/)
[![Dependency Status](https://www.versioneye.com/user/projects/53b92a84609ff04f7f000003/badge.svg)](https://www.versioneye.com/user/projects/53b92a84609ff04f7f000003)
[![HHVM Status](http://hhvm.h4cc.de/badge/aztech/events.png)](http://hhvm.h4cc.de/package/aztech/events)

## Stability

[![Latest Stable Version](https://poser.pugx.org/aztech/events/v/stable.png)](https://packagist.org/packages/aztech/events)
[![Latest Unstable Version](https://poser.pugx.org/aztech/events/v/unstable.png)](https://packagist.org/packages/aztech/events)

## Installation

### Via Composer

Composer is the only supported way of installing *aztech/events* . Don't know Composer yet ? [Read more about it](https://getcomposer.org/doc/00-intro.md).


`$ composer require "aztech/events":"~1"`

## Autoloading

Add the following code to your bootstrap file :

```
require_once 'vendor/autoload.php';
```

## Concepts

*aztech/events* aims to provide a solid foundation to build event driven architectures in PHP. 

The current mainstream approach in existing libraries is currently to produce and consume events within the same process (for example, during a single HTTP request). While this is fine in most cases,
it does not fit in well with distributed systems, where events need to cross process/host boundaries. This library attempts to solve that by decoupling totally the publish and the subscribe processes through the use of *event channels*. 

An event channel is simply any resource to which data can be written to and retrieved later (read TCP socket, memory, shared memory, files, message queues...). When an event is published, it is serialized and written to a channel, instead of being dispatched to the event subscribers. 

On the other end of the channel, a consumer is responsible for reading incoming events (synchronously or not, depending on the channel type used) and pushing them to a standard event dispatcher.

This means you can publish and dispatch events using the following methods :

    * In process (tested)
    * Out-of-process
      * Database via PDO (untested)
      * AMQP (tested)
      * STOMP (untested)
      * Wamp (publish only, partially tested)
      * Redis (untested)
      * Mixpanel (publish only, untested)
      * File (tested)
      * And more to come...
    
**TODO** : Test the untested transports

If you want to create and publish events, you will need to use a **publisher**. Currently, the library provides native support for publishing events to AMQP-compatible message queues and synchronous event publishing.

If you want to consume published events, you will need to use a **processor**. A processor is responsible for receiving events via whatever transport it uses. Currently, the library provides native support for consuming AMQP-compatible message queues and synchronous event consumption. The library provides hooks to which you can bind **subscribers**, which are simple event handlers.

Optionally, the library provides an Application object to which you can easily bind subscribers.

## Usage

For simplicity, there are factories available to create publishers and dispatchers.

Listed below are examples for some of the providers. The full documentation is available [here](./doc/providers.md).

### In process memory publish/subscribe

```php
\Aztech\Events\Bus\Plugins::loadMemoryPlugin();

$publisher = \Aztech\Events\Events::createPublisher('memory');
$processor = \Aztech\Events\Events::createProcessor('memory');

// Subscribe to all events using a wildcard filter
$processor->on('#', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

$event = \Aztech\Events\Events::create('category', array('property' => 'value'));
$dispatcher->publish($event);
```
### Event publish/subscriber via an AMQP broker

#### Publishing

```php

$options = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'user' => 'username',
    'pass' => 'password',
    'vhost' => '/',
    'exchange' => 'exchangeName'
);

\Aztech\Events\Bus\Plugins::loadAmqpPlugin();

$publisher = \Aztech\Events\Events::createPublisher('amqp', $options);
$event = \Aztech\Events\Events::create('category', array('property' => 'value'));

$publisher->publish($event);
```

#### Consuming

```php
$options = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'user' => 'username',
    'pass' => 'password',
    'vhost' => '/',
    'event-queue' => 'queueName'
);

\Aztech\Events\Bus\Plugins::loadAmqpPlugin();

$processor = $factory->createProcessor('amqp', $options);
// Subscribe to all events using a wildcard filter
$processor->on('#', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

$processor->run();
```

The `on` method accepts as a first argument a category filter expression. The internal matching engine evaluates matches on a first-match-wins basis, and accepts wildcards.

### Event publishing to WebSockets using the WAMP protocol

**Important points** 

Only publishing to a WebSocket is implemented for the time being. Publishing to a WebSocket requires
requires Ratchet to create an async event loop that you can use to publish your events.

The Wamp publisher does not create nor run a server or an event loop. Instead, the provided publisher implements
`Ratchet\Wamp\WampServerInterface` allowing to use the publisher to initialize a WampServer instance.

#### Publishing

**OUT OF DATE**

```php
// See below for $options definition
$factory = \Aztech\Events\Events::createWampFactory();
$publisher = $factory->createPublisher($options);

// Create a loop and a listening socket
$loop = \React\EventLoop\Factory::create();

// Use your loop to do some stuff, like... create or fetch events and publish them to the socket
// Here, we're just sending sample messages every second
$loop->addPeriodicTimer(1, function() use ($publisher) {
    $event = \Aztech\Events\Events::create('hello', array('time' => time());
    $publisher->publish($event);
});

// Required to get a server running
$socket = new \React\Socket\Server($loop);
$socket->listen(8080, '127.0.0.1');
$wampServer = new \Ratchet\Wamp\WampServer($publisher);
$wsServer = new \Ratchet\WebSocket\WsServer($wampServer);
$httpServer = new \Ratchet\Http\HttpServer($wsServer);
$server = \Ratchet\Server\IoServer($httpServer, $socket, $loop);

$server->run();
```

## Event matching rules

> **tl;dr** Use '#' to match absolutely anything, '*' to match exactly one unknown word.

Event category matching actually follows the [AMQP topic specification](https://svn.apache.org/repos/asf/qpid/trunk/qpid/specs/apache-filters.xml#section-legacy-amqp), which is quite flexible :

> The legacy-amqp-topic-binding filter consists of a described string value. The value value described by the type is interpreted as a pattern to match against the subject field of the Properties section of the message being evaluated.
>
> The pattern is formed using zero or more tokens, with each token delimited by the "." character. The tokens "#" and "\*" have special meanings.
> The token consisting of the single character "\*" matches a single word in the subject field.
> The token consisting of the single character "#" matches zero or more words in the subject field.
> Thus the filter value "*.stock.#" would match the subjects "usd.stock" and "eur.stock.db" but not "stock.nasdaq".

Basically, a topic name must be composed of letters and/or numbers and dashes. Sub-topics can be 
specified by using dots :

```
topic
topic.subtopic
topic.subtopic.leaf
```
You can use '*' as a wildcard to match exactly one component in a topic :

```
topic.* will match with topic.subtopic, but not with topic nor topic.subtopic.leaf
```

There is also '#', which means 0 or more components :
```
# will match all possible topics. 
topic.# will match topic, topic.subtopic, and topic.subtopic.leaf and any subtopic of topic no matter its nesting level
topic.#.leaf will match topic.subtopic.leaf and topic.other.leaf and many others, but not topic.subtopic.other
```

To get check the latest truth table of event matching, please refer to the source of `Aztech\Events\Tests\Unit\CategoryMatchTruthTable`.
