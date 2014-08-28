<?php

namespace Aztech\Events\Bus\Plugins\File;

use Aztech\Events\Bus\Channel\ReadWriteChannel;

class FileChannel extends ReadWriteChannel
{

    public function __construct($file)
    {
        $writer = new FileChannelWriter($file);
        $reader = new FileChannelReader($file);

        parent::__construct($reader, $writer);
    }
}
