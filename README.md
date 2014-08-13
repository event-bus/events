# evaneos/events

## Build status

[![Build Status](https://travis-ci.org/aztech-dev/events.png?branch=master)](https://travis-ci.org/aztech-dev/events)
[![Code Coverage](https://scrutinizer-ci.com/g/aztech-dev/events/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/aztech-dev/events/?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/aztech-dev/events/badges/quality-score.png?s=668e4df5ba163c804504257d4a026a0a549f220a)](https://scrutinizer-ci.com/g/aztech-dev/events/)
[![Dependency Status](https://www.versioneye.com/user/projects/53b92a84609ff04f7f000003/badge.svg)](https://www.versioneye.com/user/projects/53b92a84609ff04f7f000003)

## Stability

[![Latest Stable Version](https://poser.pugx.org/evaneos/events/v/stable.png)](https://packagist.org/packages/evaneos/events)
[![Latest Unstable Version](https://poser.pugx.org/evaneos/events/v/unstable.png)](https://packagist.org/packages/evaneos/events)

**Table of Contents**

- [Installation](#installation)
  - [Via Composer](#via-composer)
- [Autoloading](#autoloading)
- [Concepts](#concepts)
  - [Usage](#usage)
    - [Simple event publish/subscribe](#simple-event-publishsubscribe)
      - [Publishing](#publishing)
      - [Consuming](#consuming)
    - [Event publish/subscriber via an AMQP broker](#event-publishsubscriber-via-an-amqp-broker)
      - [Publishing](#publishing-1)
      - [Consuming](#consuming-1)
    - [Event publishing to WebSockets using the WAMP protocol](#event-publishing-to-websockets-using-the-wamp-protocol)
      - [Publishing](#publishing-2)
- [Event matching rules](#event-matching-rules)

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

$factory = \Evaneos\Events\Events::createSimpleFactory();
$publisher = $factory->createPublisher();
$event = \Evaneos\Events\Events::create('category', array('property' => 'value'));

$publisher->publish($event);
```

##### Consuming

```php
$factory = \Evaneos\Events\Events::createSimpleFactory();
$consumer = $factory->createConsumer($options);

// Subscribe to all events using a wildcard filter
$consumer->on('#', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

while (true) {
    $consumer->consumeNext();
}
```

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

$factory = \Evaneos\Events\Events::createAmqpFactory();
$publisher = $factory->createPublisher($options);
$event = new \Evaneos\Events\Events::create('category', array('property' => 'value'));

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

$factory = \Evaneos\Events\Events::createAmqpFactory();
$consumer = $factory->createConsumer($options);

// Subscribe to all events using a wildcard filter
$consumer->on('#', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

while (true) {
    $consumer->consumeNext();
}

```

The `on` method accepts as a first argument a category filter expression. The internal matching engine evaluates matches on a first-match-wins basis, and accepts wildcards.

#### Event publishing to WebSockets using the WAMP protocol

**Important points** 

Only publishing to a WebSocket is implemented for the time being. Publishing to a WebSocket requires
requires Ratchet to create an async event loop that you can use to publish your events.

The Wamp publisher does not create nor run a server or an event loop. Instead, the provided publisher implements
`Ratchet\Wamp\WampServerInterface` allowing to use the publisher to initialize a WampServer instance.

##### Publishing

```php
// See below for $options definition
$factory = \Evaneos\Events\Events::createWampFactory();
$publisher = $factory->createPublisher($options);

// Create a loop and a listening socket
$loop = \React\EventLoop\Factory::create();

// Use your loop to do some stuff, like... create or fetch events and publish them to the socket
// Here, we're just sending sample messages every second
$loop->addPeriodicTimer(1, function() use ($publisher) {
    $event = \Evaneos\Events\Events::create('hello', array('time' => time());
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

### Event matching rules

Event category matching actually follows the [AMQP topic specification](https://svn.apache.org/repos/asf/qpid/trunk/qpid/specs/apache-filters.xml#section-legacy-amqp), which is quite flexible :

> **tl;dr** Use '#' to match absolutely anything, '*' to match exactly one unknown word.

> The legacy-amqp-topic-binding filter consists of a described string value. The value value described by the type is interpreted as a pattern to match against the subject field of the Properties section of the message being evaluated.
>
> The pattern is formed using zero or more tokens, with each token delimited by the "." character. The tokens "#" and "*" have special meanings.
> The token consisting of the single character "*" matches a single word in the subject field.
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

To get check the latest truth table of event matching, please refer to the source of `Evaneos\Events\Tests\Unit\CategoryMatchTruthTable`.
