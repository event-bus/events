<?php

use Aztech\Events\Events;
use Aztech\Events\Bus\Plugins\ZeroMq\Plugin;
use Aztech\Events\Bus\Event;
use Aztech\Events\Bus\Serializer\JsonSerializer;

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
