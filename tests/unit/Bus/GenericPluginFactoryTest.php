<?php

namespace Aztech\Events\Tests;

use Aztech\Events\Bus\GenericPluginFactory;
use Aztech\Events\Bus\Factory\GenericOptionsDescriptor;

class GenericPluginFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDescriptorReturnsProvidedInstance()
    {
        $providerBuilder = function ()
        {};

        $descriptor = new GenericOptionsDescriptor();
        $factory = new GenericPluginFactory($providerBuilder, $descriptor);

        $this->assertSame($descriptor, $factory->getOptionsDescriptor());
    }

    public function testGetProviderReturnsCallbackResult()
    {
        $anchor = new \stdClass();

        $providerBuilder = function () use ($anchor) {
            return $anchor;
        };

        $factory = new GenericPluginFactory($providerBuilder);

        $this->assertSame($anchor, $factory->getChannelProvider());
    }
}
