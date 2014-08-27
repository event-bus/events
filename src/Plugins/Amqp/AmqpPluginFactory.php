<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use Aztech\Events\Serializer;
use Aztech\Events\Bus\Serializer\NativeSerializer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Aztech\Events\Bus\PluginFactory;

class AmqpPluginFactory implements PluginFactory
{
    private $descriptor;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->descriptor = new AmqpOptionsDescriptor();
    }

    public function getOptionsDescriptor()
    {
        return $this->descriptor;
    }

    public function getChannelProvider()
    {
        return new AmqpChannelProvider();
    }
}
