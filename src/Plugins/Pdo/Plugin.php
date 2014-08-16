<?php

namespace Aztech\Events\Plugins\Pdo;

use Aztech\Events\Serializer;
use Aztech\Events\Core\AbstractPlugin;
use Aztech\Events\Core\Serializer\NativeSerializer;

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
