<?php

namespace Aztech\Events\Tests\Bus\Plugins\File;

use org\bovigo\vfs\vfsStream;
use Aztech\Events\Bus\Plugins\File\FileChannelReader;

class FileChannelReaderTest extends \PHPUnit_Framework_TestCase
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

        file_put_contents($this->file, $this->data);
    }

    public function testReadReturnsCorrectData()
    {
        $reader = new FileChannelReader($this->root->url() . '/test.queue');

        $this->assertEquals($this->data, $reader->read());
    }

    public function testMultipleReadsReturnsCorrectData()
    {
        $reader = new FileChannelReader($this->root->url() . '/test.queue');

        file_put_contents($this->file, PHP_EOL . strrev($this->data), FILE_APPEND);

        $this->assertEquals($this->data, $reader->read());
        $this->assertEquals(strrev($this->data), $reader->read());
    }
}
