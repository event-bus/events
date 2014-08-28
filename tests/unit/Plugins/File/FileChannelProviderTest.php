<?php

namespace Aztech\Events\Tests\Bus\Plugins\File;

use Aztech\Events\Bus\Plugins\File\FileChannelProvider;
class FileChannelProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateReturnsFileChannel()
    {
        $options = array('file' => '/tmp/test.queue');
        $provider = new FileChannelProvider();
        $channel = $provider->createChannel($options);

        $this->assertInstanceOf('\Aztech\Events\Bus\Plugins\File\FileChannel', $channel);
    }
}
