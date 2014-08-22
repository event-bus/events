<?php

use Aztech\Events\Bus\Dispatcher;
use Aztech\Events\Bus\Consumer;
use Aztech\Events\Bus\Publisher\SynchronousPublisher;
use Aztech\Events\Bus\Subscriber\CallbackSubscriber;
use Aztech\Events\Event;
use Aztech\Events\Events;
use Aztech\Events\Bus\AbstractEvent;
use Aztech\Events\EventDispatcher;

include_once __DIR__ . '/../vendor/autoload.php';

$dispatcher = new EventDispatcher();
$publisher = new SynchronousPublisher($dispatcher);

// Notice that you have to bind your listeners before any events are published
$dispatcher->addListener('#', new CallbackSubscriber(function(AbstractEvent $event) {
  echo 'Received event : ' . $event->getCategory() . PHP_EOL;
  echo 'Event properties : ' . PHP_EOL;
  foreach ($event->getProperties() as $name => $value) {
      echo '    ' . $name . ' = ' . $value . PHP_EOL;
  }

  echo PHP_EOL;
}));

$publisher->publish(Events::create('test', array('property' => 'value', 'other' => 'value')));


