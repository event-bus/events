<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Factory\OptionsDescriptor;

interface PluginFactory
{

    /**
     *
     * @return OptionsDescriptor
     */
    function getOptionsDescriptor();

    /**
     *
     * @return ChannelProvider
     */
    function getChannelProvider();
}
