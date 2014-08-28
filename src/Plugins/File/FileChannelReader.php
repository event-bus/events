<?php

namespace Aztech\Events\Bus\Plugins\File;

use Aztech\Events\Bus\Channel\ChannelReader;
use Aztech\Util\File\Files;

class FileChannelReader implements ChannelReader
{

    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function read()
    {
        $data = false;

        while (! $data) {
            $data = $this->readNextLine();
            $this->checkDataBlock($data);
        }

        return $data;
    }

    private function readNextLine()
    {
        $data = false;

        if ($handle = fopen($this->file, "c+")) {
            $data = Files::invokeEx(array(
                $this,
                'readAndRemoveFirstLine'
            ), $handle);

            fclose($handle);
        }

        return $data;
    }

    private function checkDataBlock($data)
    {
        if (! $data) {
            // @codeCoverageIgnoreStart
            usleep(250000);
        }
        // @codeCoverageIgnoreEnd
    }

    public function readAndRemoveFirstLine($handle)
    {
        $lines = array();
        $data = false;

        while (($line = fgets($handle)) !== false) {
            if ($data) {
                $lines[] = trim($line);
            }
            elseif (trim($line) != '') {
                $data = trim($line);
            }
        }

        file_put_contents($this->file, implode(PHP_EOL, $lines));

        return $data;
    }
}
