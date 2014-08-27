<?php

namespace Aztech\Events\Bus\Plugins\Wamp;

use Aztech\Events\Serializer;
use Aztech\Events\Bus\GenericPlugin;
use Aztech\Events\Bus\Serializer\JsonSerializer;

class Plugin extends GenericPlugin
{

    public function __construct(Serializer $serializer = null)
    {
        $factory = new Factory($serializer ?  : new JsonSerializer());

        $this->setFactory($factory);
        $this->setProcessFlag(false);
        $this->setPublishFlag(true);
    }
}
