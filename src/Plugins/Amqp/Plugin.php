<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use Aztech\Events\Serializer;
use Aztech\Events\Bus\GenericPlugin;
use Aztech\Events\Bus\Serializer\NativeSerializer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Plugin extends GenericPlugin
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
