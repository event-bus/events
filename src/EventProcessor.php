<?php

namespace Evaneos\Events;

interface EventProcessor
{

    const EVENT_PROCESSED = 'processed';

    const EVENT_PROCESSING = 'processing';

    const EVENT_ERROR = 'error';

    /**
     * Processes the next available event and submits it to the given event dispatcher
     * @param EventDispatcher $dispatcher
     */
    function processNext(EventDispatcher $dispatcher);

    function on($name, EventSubscriber $subscriber);
}
