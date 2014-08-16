<?php

namespace Aztech\Events;

interface Factory
{

    /**
     *
     * @param array $options
     * @return \Aztech\Events\Publisher
     */
    function createPublisher(array $options = array());

    /**
     *
     * @param array $options
     * @return \Aztech\Events\Processor
     */
    function createProcessor(array $options = array());

    /**
     *
     * @return \Aztech\Events\Consumer
     */
    function createConsumer(array $options = array());
}
