<?php

namespace Aztech\Events\Bus\Plugins;

use Aztech\Events\Bus\Events;
use Aztech\Events\Bus\Factory\GenericOptionsDescriptor;
use Aztech\Events\Bus\GenericPluginFactory;
use Aztech\Events\Bus\Plugins\Amqp\AmqpPluginFactory;
use Aztech\Events\Bus\Plugins\Memory\MemoryChannelProvider;
use Aztech\Events\Bus\Plugins\File\FileChannel;
use Aztech\Events\Bus\Plugins\Mixpanel\MixpanelOptionsDescriptor;
use Aztech\Events\Bus\Plugins\Mixpanel\MixpanelChannelProvider;
use Aztech\Events\Bus\Plugins\Stomp\StompChannelProvider;
use Aztech\Events\Bus\Plugins\Stomp\StompOptionsDescriptor;
use Aztech\Events\Bus\Plugins\File\FileChannelProvider;
use Aztech\Events\Bus\Plugins\Socket\SocketChannelProvider;
use Aztech\Events\Bus\Plugins\Socket\SocketOptionsDescriptor;

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

        Events::addPlugin('file', new GenericPluginFactory(function ()
        {
            return new FileChannelProvider();
        }), $descriptor);
    }

    static function loadMemoryPlugin($name = 'memory')
    {
        Events::addPlugin($name, new GenericPluginFactory(function ()
        {
            return new MemoryChannelProvider();
        }));
    }

    static function loadMixpanelPlugin($name = 'mixpanel')
    {
        Events::addPlugin($name, new GenericPluginFactory(function ()
        {
            return new MixpanelChannelProvider();
        }, new MixpanelOptionsDescriptor()));
    }

    static function loadSocketPlugin($name = 'socket')
    {
        Events::addPlugin($name, new GenericPluginFactory(function ()
        {
            return new SocketChannelProvider();
        }, new SocketOptionsDescriptor()));
    }

    static function loadStompPlugin($name = 'stomp')
    {
        Events::addPlugin($name, new GenericPluginFactory(function ()
        {
            return new StompChannelProvider();
        }, new StompOptionsDescriptor()));
    }
}
