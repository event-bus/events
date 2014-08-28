# aztech/events

## Important notice

This package is the main repository for aztech/events, and does not contain any code. Please refer
to the composer.json to view the list of the packages provided by this package.

## Stability

[![Latest Stable Version](https://poser.pugx.org/aztech/events/v/stable.png)](https://packagist.org/packages/aztech/events)
[![Latest Unstable Version](https://poser.pugx.org/aztech/events/v/unstable.png)](https://packagist.org/packages/aztech/events)

## Installation

### Via Composer

Composer is the only supported way of installing *aztech/events* . Don't know Composer yet ? [Read more about it](https://getcomposer.org/doc/00-intro.md).


```bash
$ composer require "aztech/events":"~1"
```

## Autoloading

Add the following code to your bootstrap file :

```php
require_once 'vendor/autoload.php';
```

## Concepts

*aztech/events* aims to provide a solid foundation to build event driven architectures in PHP. 

The current mainstream approach in existing libraries is currently to produce and consume events within the same process (for example, during a single HTTP request). While this is fine in most cases,
it does not fit in well with distributed systems, where events need to cross process/host boundaries. This library attempts to solve that by decoupling totally the publish and the subscribe processes through the use of *event channels*. 

An event channel is simply any resource to which data can be written to and retrieved later (read TCP socket, memory, shared memory, files, message queues...). When an event is published, it is serialized and written to a channel, instead of being dispatched to the event subscribers. 

On the other end of the channel, a consumer is responsible for reading incoming events (synchronously or not, depending on the channel type used) and pushing them to a standard event dispatcher. The dispatcher used by the consumer is responsible for dispatching received events to your handlers.

This means you can publish and dispatch events using the following methods :

  * [Native plugins](https://github.com/aztech-dev/event-bus-core-plugins)
      * [File](https://github.com/aztech-dev/event-bus-core-plugins/blob/master/doc/File.md)
      * [Socket](https://github.com/aztech-dev/event-bus-core-plugins/blob/master/doc/Socket.md)
      * [Memory](https://github.com/aztech-dev/event-bus-core-plugins/blob/master/doc/Memory.md)
      * [Database via PDO (untested)](https://github.com/aztech-dev/event-bus-core-plugins/blob/master/doc/Pdo.md)
  * [Extra plugins](https://github.com/aztech-dev/event-bus-extra-plugins)
      * [AMQP](https://github.com/aztech-dev/event-bus-extra-amqp)
      * [Mixpanel](https://github.com/aztech-dev/event-bus-extra-mixpanel)
      * [STOMP](https://github.com/aztech-dev/event-bus-extra-stomp)
      * [Redis](https://github.com/aztech-dev/event-bus-extra-redis)
      * [Wamp](https://github.com/aztech-dev/event-bus-extra-wamp)
      * [ZMQ](https://github.com/aztech-dev/event-bus-extra-zmq)

If you want to create and publish events, you will need to use a **publisher**.
If you want to consume published events, you will need to use a **processor**. 
A processor is responsible for receiving events via whatever transport it uses. The library provides hooks to which you can bind **subscribers**, which are simple event handlers.

Optionally, the library provides an Application object to which you can easily bind subscribers.


## Event matching rules

In *events*, all events are published and subscribed to on topic basis. An event has to expose a category
property, which must return the topic to which the event belongs. It can be any word, or chain of words separated by dots. Each dot indicates a sub-topic.

Subscribers can then choose to subscribe to topic on an exact match basis, or by using wildcards to subscribe to multiple events.

### Using wildcards

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

## Usage

For simplicity, there are factories available to create publishers and dispatchers.

Listed below are examples for some of the providers. The full documentation is available [here](https://github.com/aztech-dev/events/tree/master/doc/plugins.md).

### Basic usage

```php

require_once 'vendor/autoload.php';

use \Aztech\Events\Bus\Events;
use \Aztech\Events\Bus\Plugins\Plugins;

Plugins::loadMemoryPlugin();

$publisher = Events::createPublisher('memory');
$processor = Events::createProcessor('memory');

// Subscribe to all events using a wildcard filter
$processor->on('#', function (Event $event) {
    echo 'Received a new event : ' . $event->getCategory();
});

$event = \Aztech\Events\Events::create('category', array('property' => 'value'));
$publisher->publish($event);
```

Checkout the provider specific documentations for more usage examples.
