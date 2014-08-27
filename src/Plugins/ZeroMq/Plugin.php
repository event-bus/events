<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq;

use Aztech\Events\Serializer;
use Aztech\Events\Bus\GenericPlugin;
use Aztech\Events\Bus\Serializer\NativeSerializer;

class Plugin extends GenericPlugin
{

    public function __construct(Serializer $serializer = null)
    {
        $factory = new Factory($serializer ?: new NativeSerializer());

        $this->setFactory($factory);
        $this->setProcessFlag(true);
        $this->setPublishFlag(true);
    }
}
