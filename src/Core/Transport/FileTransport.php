<?php

namespace Aztech\Events\Core\Transport;

use Aztech\Events\Event;
use Aztech\Events\Transport;

class FileTransport implements Transport
{

    private $file;

    public function __construct($file)
    {
        $this->file = $file;

        if (! file_exists($this->file)) {
            file_put_contents($this->file, '');
        }
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
            if (flock($handle, LOCK_EX)) {
                $data = $this->readFile($handle);
                flock($handle, LOCK_UN);
            }

            fclose($handle);
        }

        return $data;
    }

    private function checkDataBlock($data)
    {
        if (! $data) {
            usleep(250000);
        }
    }

    private function readFile($handle)
    {
        $lines = array();

        while (($line = fgets($handle)) !== false) {
            if (isset($data)) {
                $lines[] = trim($line);
            }
            elseif (trim($line) != '') {
                $data = trim($line);
            }
        }

        file_put_contents($this->file, implode(PHP_EOL, $lines));

        return isset($data) ? $data : false;
    }

    public function write(Event $event, $serializedEvent)
    {
        if ($handle = fopen($this->file, "c+")) { // open the file in reading and editing mode
            if (flock($handle, LOCK_EX)) { // lock the file, so no one can read or edit this file
                while (($line = fgets($handle) !== false)) {
                    continue;
                }

                fwrite($handle, $serializedEvent . PHP_EOL);
                fflush($handle); // write any pending change to file
                flock($handle, LOCK_UN); // unlock the file
            }
            fclose($handle);
        }

        return null;
    }
}
