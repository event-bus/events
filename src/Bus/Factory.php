<?php

namespace Aztech\Events\Bus;

interface Factory
{

    /**
     *
     * @param array $options
     * @return \Aztech\Events\Bus\Publisher
     */
    function createPublisher(array $options = array());

    /**
     *
     * @param array $options
     * @return \Aztech\Events\Bus\Processor
     */
    function createProcessor(array $options = array());

}
