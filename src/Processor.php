<?php

namespace Aztech\Events;

interface Processor
{

    const EVENT_NODE_STOP = 'stop';

    const EVENT_PROCESSED = 'processed';

    const EVENT_PROCESSING = 'processing';

    const EVENT_ERROR = 'error';

    /**
     * Processes the next available event and submits it to the given event dispatcher
     * @param Dispatcher $dispatcher
     */
    function processNext(Dispatcher $dispatcher);

    /**
     * Subscribe to processing events
     * @param string $categoryFilter
     * @param Subscriber $subscriber
     * @return void
     */
    function on($categoryFilter, Subscriber $subscriber);
}
