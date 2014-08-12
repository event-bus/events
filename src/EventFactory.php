<?php

namespace Evaneos\Events;

use Evaneos\Events\Publishers\SynchronousEventPublisher;
use Evaneos\Events\Publishers\CompositePublisher;

/**
 * @obsolete
 * @author thibaud
 *
 */
class EventFactory
{

    private $dispatcher;

    private $publisher;

    private function initializeDispatcher($force = false)
    {
        if (self::$dispatcher == null || $force) {
            self::$dispatcher = new NullDispatcher();
        }
    }

    private function initializePublisher($force = false)
    {
        if (self::$publisher == null || $force) {
            self::$publisher = new SynchronousEventPublisher(self::getDispatcher());
        }
    }

    public function setDispatcher(EventDispatcher $dispatcher)
    {
        self::$dispatcher = $dispatcher;
    }

    public function registerPublisher(EventPublisher $publisher)
    {
        if (self::$publisher !== null) {
            if (! (self::$publisher instanceof CompositePublisher)) {
                $composite = new CompositePublisher();

                $composite->addPublisher(self::$publisher);

                self::$publisher = $composer;
            }

            self::$publisher->addPublisher($publisher);
        }
        else {
            self::$publisher = $publisher;
        }
    }

    public function getDispatcher()
    {
        self::initializeDispatcher(false);

        return self::$dispatcher;
    }

    public function getPublisher()
    {
        self::initializeListener(false);

        return self::$listener;
    }
}
