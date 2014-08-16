# Event dispatching

**Notice** All examples assume you are using an autoloader.

In `events`, you can do both *in-process* and *out-of-process* event processing.

*In-process* event processing means that your events publishers and subscribers live in the same application thread. Symfony's event dispatcher is an example of this. Consumers actually subscribe to the same
object that is used to emit the events. With `events`, the Simple provider acts as an in-process event mechanism.

*Out-of-process* event processing means that your events are published to another application thread that exists outside of the current app. Providers such as AMQP, Stomp, etc, provide that feature by serializing events to transport them over to another application by whatever transport they want. 


## Basic dispatch - in-process

If you are in a hurry, you can use the simplest dispatch available with `\Evaneos\Events\Core\Dispatcher`. It is a standard dispatcher like you would find in any event library.

```php
$dispatcher = new \Aztech\Events\Core\Dispatcher();
$subscriber = new \Aztech\Events\Core\CallbackSubscriber(function (\Aztech\Events\Event $event) {
    echo 'Received event id #' . $event->getId() . 
         ' with category ' . $event->getCategory() . PHP_EOL;
});

$dispatcher->on('#', $subscriber);
$dispatcher->publish(new \Aztech\Events\Core\Event('test', array('property' => 'value'));
```

If you only need a lightweight dispatcher, you have it here. It uses a pseudo Trie tree for category matching, inspired by Rabbit MQ's own description of their Trie implementation for topic matching, which gives
you advanced topic matching with support for '*' and '#' wildcards as per the AMQP specification.

## Less basic dispatch - out-of-process

The standard dispatcher unfortunately does not allow to publish out-of-process. The library provides a higher-level of abstraction to achieve that.