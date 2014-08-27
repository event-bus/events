<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\File\FileChannel;
use org\bovigo\vfs\vfsStream;

class FileChannelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testWriteThenReadReturnsSameData()
    {
        $root = vfsStream::setup();

        $event = $this->getMock('Aztech\Events\Event');
        $serialized = 'some data';

        $transport = new FileChannel($root->url() . '/test.queue');

        $transport->write($event, $serialized);
        $data = $transport->read();

        $this->assertEquals($serialized, $data);
    }

    public function testMultipleWritesThenReadsReturnsSameDataInOrder()
    {
        $root = vfsStream::setup();

        $event = $this->getMock('Aztech\Events\Event');
        $second = $this->getMock('Aztech\Events\Event');
        $serialized = 'some data';
        $secondSerialized = 'other data';

        $transport = new FileChannel($root->url() . '/test.queue');

        $transport->write($event, $serialized);
        $transport->write($second, $secondSerialized);

        $data = $transport->read();
        $secondData = $transport->read();

        $this->assertEquals($serialized, $data);
        $this->assertEquals($secondSerialized, $secondData);
    }
}
