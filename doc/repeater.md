# Event repeater

Using the core classes, you can easily create a repeater daemon that forwards received events to another event publisher.
This can be useful to create for example an intermediate event broker with round-robin dispatching to a set of workers.

## Simple repeater

**Repeater daemon**

```php
<?php 

require_once 'vendor/autoload.php';

use \Aztech\Events\Bus\Plugins\Plugins;
use \Aztech\Events\Bus\Events;

Plugins::loadSocketPlugin('sock');
Plugins::loadFilePlugin('file');

$sockOpts = array(
    'protocol' => 'ipv4',
    'host' => '127.0.0.1',
    'port' => 8088
);

$fileOpts = array(
    'file' => '/tmp/events.queue'
);

$processor = Events::createProcessor('sock', $sockOpts);
$publisher = Events::createPublisher('file', $fileOpts);

$bridge = Events::bridge($processor, $publisher, '#');
// This method blocks and run forever
$bridge->run();
```

**Consuming the repeated events**

```php
<?php 

require_once 'vendor/autoload.php';

use \Aztech\Events\Event;
use \Aztech\Events\Bus\Events;
use \Aztech\Events\Bus\Plugins\Plugins;

Plugins::loadFilePlugin('file');

$fileOpts = array(
    'file' => '/tmp/events.queue'
);

$application = Events::createApplication('file', $fileOpts);
$application->on('#', function(Event $event) {
    echo $event->getCategory() . ' : received event #' . $event->getId() . PHP_EOL;
});

// This method runs forever
$application->run();

```

