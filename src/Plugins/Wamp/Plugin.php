<?php

namespace Aztech\Events\Plugins\Wamp;

use Aztech\Events\Serializer;
use Aztech\Events\Core\AbstractPlugin;
use Aztech\Events\Core\Serializer\NativeSerializer;
use Aztech\Events\Core\Serializer\JsonSerializer;

class Plugin extends AbstractPlugin
{

    public function __construct(Serializer $serializer = null)
    {
        $factory = new Factory($serializer ?: new JsonSerializer());

        $this->setFactory($factory);
        $this->setProcessFlag(false);
        $this->setPublishFlag(true);
    }

}
