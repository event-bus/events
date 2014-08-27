<?php

namespace Aztech\Events\Bus\Plugins\File;

use Aztech\Events\Event;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class FileChannel extends ReadWriteChannel
{

    private $file;

    public function __construct($file)
    {
        $writer = new FileChannelWriter($file);
        $reader = new FileChannelReader($file);

        $this->file = $file;

        if (! file_exists($this->file)) {
            file_put_contents($this->file, '');
        }

        parent::__construct($reader, $writer);
    }
}
