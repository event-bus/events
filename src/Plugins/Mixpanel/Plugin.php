<?php

namespace Aztech\Events\Bus\Plugins\Mixpanel;

use Aztech\Events\Serializer;
use Aztech\Events\Bus\GenericPlugin;
use Aztech\Events\Bus\Serializer\NativeSerializer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Aztech\Events\Bus\GenericFactory;

class Plugin extends GenericPlugin
{

    public function __construct(Serializer $serializer = null, LoggerInterface $logger = null)
    {
        $serializer = $serializer ? : new NativeSerializer();
        $logger = $logger ?: new NullLogger();
        $descriptor = new MixpanelOptionsDescriptor();
        $channelProvider = new MixpanelChannelProvider();

        $factory = new GenericFactory($serializer, $channelProvider, $descriptor);

        $this->setFactory($factory);
        $this->setProcessFlag(true);
        $this->setPublishFlag(true);
    }
}
