<?php

namespace Evaneos\Events\Factory;

class StandardFactory implements Factory
{

    function createPublisher(array $options = array());

    function createProcessor(array $options = array());

    function createConsumer(array $options = array());

}
