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

// Here we only publish a single event to release cleanly example main scripts. We could manually kill it.
$publisher = $factory->createPublisher();
$publisher->publish(Events::create('test.out.of.process.release'));
