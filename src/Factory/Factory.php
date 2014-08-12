<?php

namespace Evaneos\Events\Factory;

interface Factory {

    function createPublisher(array $options = array());

    function createDispatcher(array $options = array());

    function createProcessor(array $options = array());

    function createConsumer(array $options = array());
}
