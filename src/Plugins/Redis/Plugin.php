<?php

namespace Aztech\Events\Plugins\Redis;

use Aztech\Events\Serializer;
use Aztech\Events\Bus\AbstractPlugin;
use Aztech\Events\Bus\Serializer\NativeSerializer;

class Plugin extends AbstractPlugin
{

    public function __construct(Serializer $serializer = null)
    {
        $factory = new Factory($serializer ?  : new NativeSerializer());

        $this->setFactory($factory);
        $this->setProcessFlag(true);
        $this->setPublishFlag(true);
    }
}
