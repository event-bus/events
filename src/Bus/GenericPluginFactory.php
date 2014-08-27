<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Factory\NullOptionsDescriptor;
use Aztech\Events\Bus\Factory\OptionsDescriptor;

class GenericPluginFactory implements PluginFactory
{

    private $providerCallback;

    private $descriptor;

    public function __construct(callable $providerBuilder, OptionsDescriptor $descriptor = null)
    {
        $this->providerCallback = $providerBuilder;
        $this->descriptor = $descriptor ?: new NullOptionsDescriptor();
    }

    public function getOptionsDescriptor()
    {
        return $this->descriptor;
    }

    public function getChannelProvider()
    {
        return call_user_func($this->providerCallback);
    }
}
