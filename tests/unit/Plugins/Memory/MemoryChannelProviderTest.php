<?php

namespace Aztech\Events\Tests\Bus\Plugins\Memory;

use Aztech\Events\Bus\Plugins\Memory\MemoryChannelProvider;
class MemoryChannelProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateChannelReturnsReadWriteChannel()
    {
        $provider = new MemoryChannelProvider();
        $channel = $provider->createChannel();

        $this->assertTrue($channel->canRead());
        $this->assertTrue($channel->canWrite());

        $this->assertInstanceOf('\Aztech\Events\Bus\Plugins\Memory\MemoryChannelReaderWriter', $channel->getReader());
        $this->assertInstanceOf('\Aztech\Events\Bus\Plugins\Memory\MemoryChannelReaderWriter', $channel->getWriter());
    }

}
