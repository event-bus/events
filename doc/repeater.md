# Event repeater

Using the core classes, you can easily create a repeater daemon that forwards received events to another event publisher.
This can be useful to create for example an intermediate event broker with round-robin dispatching to a set of workers.

## Simple repeater

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
