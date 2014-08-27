<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Dispatcher;
use Aztech\Events\Subscriber;

/**
 * @author thibaud
 *
 */
interface Processor
{

    /**
     * Processes the next available event and submits it to the given event dispatcher
     * @param Dispatcher $dispatcher
     */
    function processNext(Dispatcher $dispatcher);

}
