# AMQP Channel provider

## Dependencies

  * videlalvaro/php-amqplib : ~2

## Supported elements :

  * Publish
  * Subscribe

## Configuration options & defaults

| Parameter | Default | Description |
|--------------|-------------|-------------------------------------------------------------------------------------------|
| `host` | `127.0.0.1` | Hostname of the AMQP broker. |
| `port` | `5672` | Listening port of the AMQP broker. |
| `user` | `guest` | AMQP broker username. |
| `pass` | `guest` | AMQP broker password. |
| `vhost` | `/` | Virtual host name on the AMQP broker. |
| `exchange` | `exchange` | Name of the exchange. |
| `event-queue` | `event-queue` | Name of the event queue. |
| `event-prefix` | ` ` | Prefix that will be automatically added to published/stripped from received event topics. |
| `auto-create` | `true` | Toggles the providers topology creation feature. Allows to auto-create the required exchanges, queues, and bindings for the provider's need. |

## Initialization

```php
include __DIR__ . '/vendor/autoload.php';

\Aztech\Events\Bus\Plugins::loadAmqpPlugin();

$options = array(...);

$publisher = \Aztech\Events\Events::createPublisher('amqp', $options);
$event = \Aztech\Events\Events::create('category', array('property' => 'value'));

$publisher->publish($event);
// ...
```

## Caveats

At the time being, the AMQP event plugin uses topic based routing to publish events. Multiple nodes connecting to a single queue will work in round-robin mode.

It is possible to use different routing scenarios/exchange types, but that is left as an exercise to the reader (Hint: no need to build/patch the current plugin).
