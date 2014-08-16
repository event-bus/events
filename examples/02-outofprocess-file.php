<?php

use Aztech\Events\Events;
use Aztech\Events\Core\Transport\FileTransport;
use Aztech\Events\Core\AbstractEvent;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

include_once __DIR__ . '/../vendor/autoload.php';

$logger = new ConsoleLogger(new ConsoleOutput(ConsoleOutput::VERBOSITY_DEBUG));
$logger->info('Starting example 2');

// Events will be queued in a file
$transport = new FileTransport('/tmp/events.queue');

// Create a factory to get access to publishers and consumers
$factory = Events::createFactory($transport, null, $logger);

// Unlike in-process events, out-of-process events are persisted until a consumer is available
// and actively starts consuming. You can hence publish them before setting up your subscribers.
$publisher = $factory->createPublisher();
$consumer = $factory->createConsumer();

$publisher->publish(Events::create('test.out.of.process'));
$publisher->publish(Events::create('other.out.of.process'));

// Bind a callback to an event category
$consumer->on('test.#', function ($event) use($logger) {
    $logger->alert('This is is a critical event :p : ' . $event->getCategory());
    usleep(250000);
});

// Bind a callback for every event
$consumer->on('#', function ($event) use($logger) {
    $logger->info('Received event : ' . $event->getCategory());
    usleep(250000);
});

// Normally, this should run in an application loop outside of the publishing app.
$consumer->consumeNext();
$consumer->consumeNext();

// This call will block
$logger->alert('Next call is blocking. Run 02-outofprocess-file-release.php to send an event to the consumer.');
$consumer->consumeNext();
