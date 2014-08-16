<?php

use Aztech\Events\Events;
use Aztech\Events\Plugins\ZeroMq\Plugin;
use Aztech\Events\Core\Event;
use Aztech\Events\Core\Serializer\JsonSerializer;

include_once __DIR__ . '/../vendor/autoload.php';

Events::addPlugin('zmq', new Plugin(new JsonSerializer()));
$factory = Events::getPluginFactory('zmq');

$publisher = $factory->createPublisher(array('multicast' => true));

$event = new Event('test');


while (true) {
    $publisher->publish($event);
    sleep(1);
}

$factory = null;
$publisher = null;
