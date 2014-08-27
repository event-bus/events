<?php

use Aztech\Events\Events;
use Aztech\Events\Bus\Plugins\ZeroMq\Plugin;
use Aztech\Events\Bus\Serializer\JsonSerializer;

include_once __DIR__ . '/../vendor/autoload.php';

Events::addPlugin('zmq', new Plugin(new JsonSerializer()));
$factory = Events::getPluginFactory('zmq');

$consumer = $factory->createConsumer(array('multicast' => true));

$consumer->on('#', function ($event) {
    echo 'Received event : ' . $event->getId() . ' ==> ' . $event->getCategory() . PHP_EOL;
});

// Normally, this should run in an application loop outside of the publishing app.
while (true) {
    $consumer->consumeNext();
}

