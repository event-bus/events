<?php

namespace Aztech\Events\Bus\Plugins\File;

use Aztech\Events\Bus\Channel\ChannelProvider;

class FileChannelProvider implements ChannelProvider
{

    public function createChannel(array $options = array())
    {
        return new FileChannel($options['file']);
    }
}
