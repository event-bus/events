<?php

namespace Aztech\Events\Tests\Bus\Plugins\File;

use org\bovigo\vfs\vfsStream;
use Aztech\Events\Bus\Plugins\File\FileChannelWriter;

class FileChannelWriterTest extends \PHPUnit_Framework_TestCase
{

    private $file;

    private $root;

    private $data;

    protected function setUp()
    {
        $this->root = vfsStream::setup();
        $this->file = $this->root->url() . '/test.queue';

        $data = range('A', 'Z');
        shuffle($data);
        $data = join('', $data);

        $this->data = $data;
    }

    public function testDataIsWrittenToFile()
    {
        $writer = new FileChannelWriter($this->file);

        $writer->write($this->getMock('\Aztech\Events\Event'), $this->data);
        $data = file_get_contents($this->file);

        $this->assertEquals($this->data, trim($data));
    }

    public function testMultipleWritesThenReadsReturnsSameDataInOrder()
    {
        $event = $this->getMock('Aztech\Events\Event');
        $second = $this->getMock('Aztech\Events\Event');
        $serialized = 'some data';
        $secondSerialized = 'other data';

        $writer = new FileChannelWriter($this->file);

        $writer->write($event, $serialized);
        $writer->write($second, $secondSerialized);

        list($data, $secondData) = explode(PHP_EOL, file_get_contents($this->file));

        $this->assertEquals($serialized, $data);
        $this->assertEquals($secondSerialized, $secondData);
    }

    public function testWriterIgnoresWriteWithoutLock()
    {
        $file = fopen($this->file, 'w');
        flock($file, LOCK_EX);

        $event = $this->getMock('Aztech\Events\Event');
        $serialized = 'some data';
        $writer = new FileChannelWriter($this->file);

        $writer->write($event, $serialized);

        $this->assertEmpty(file_get_contents($this->file));
    }
}
