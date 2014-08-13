<?php

namespace Aztech\Events;

interface Factory {

    function createPublisher(array $options = array());

    function createProcessor(array $options = array());

    /**
     * @return Core\Consumer
     */
    function createConsumer(array $options = array());
}
