<?php

namespace Aztech\Events\Bus;

interface Factory
{

    /**
     *
     * @param array $options
     * @return Publisher
     */
    function createPublisher(array $options = array());

    /**
     *
     * @param array $options
     * @return Processor
     */
    function createProcessor(array $options = array());
}
