<?php

namespace Aztech\Events\Plugins\Amqp;

use Aztech\Events\Serializer;
use Aztech\Events\Core\AbstractPlugin;
use Aztech\Events\Core\Serializer\NativeSerializer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Plugin extends AbstractPlugin
{

    public function __construct(Serializer $serializer = null, LoggerInterface $logger = null)
    {
        $factory = $serializer ? : new NativeSerializer();
        $logger = $logger ?: new NullLogger();

        $this->setFactory($factory);
        $this->setProcessFlag(true);
        $this->setPublishFlag(true);
    }
}
