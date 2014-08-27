<?php

namespace Aztech\Events\Bus\Plugins;

use Aztech\Events\Bus\Events;
use Aztech\Events\Bus\Plugins\Amqp\AmqpPluginFactory;
use Aztech\Events\Bus\Plugins\Memory\MemoryChannelProvider;
use Aztech\Events\Bus\GenericPluginFactory;
use Aztech\Events\Bus\Plugins\File\FileChannel;
use Aztech\Events\Bus\Factory\GenericOptionsDescriptor;
use Aztech\Events\Bus\Plugins\Mixpanel\MixpanelOptionsDescriptor;
use Aztech\Events\Bus\Plugins\Mixpanel\MixpanelChannelProvider;

class Plugins
{

    static function loadAmqpPlugin()
    {
        Events::addPlugin('amqp', new AmqpPluginFactory());
    }

    static function loadFilePlugin()
    {
        $descriptor = new GenericOptionsDescriptor();
        $descriptor->addOption('file');

        Events::addPlugin('file', new GenericPluginFactory(function () {
            return new FileChannelProvider();
        }), $descriptor);
    }

    static function loadMemoryPlugin($name = 'memory')
    {
        Events::addPlugin($name, new GenericPluginFactory(function() {
            return new MemoryChannelProvider();
        }));
    }

    static function loadMixpanelPlugin($name = 'mixpanel')
    {
        Events::addPlugin($name, new GenericPluginFactory(function() {
            return new MixpanelChannelProvider();
        }, new MixpanelOptionsDescriptor()));
    }
}
