<?php

namespace Aztech\Events\Core\Transport;

use Aztech\Events\Transport\Writer;

class FileWriter implements Writer
{
    private $file;
    
    public function __construct($file)
    {
        $this->file = $file;
    }
    
    public function write(Event $event, $serializedEvent)
    {
        if ($handle = fopen($this->file, "c+")) {
            if ($this->callEx($handle, array($this, 'append'))) {
                fflush($handle);
            }
            
            fclose($handle);
        }
    }
    
    private function callEx($handle, $data, $callback)
    {
        if (flock($handle, LOCK_EX)) {
            $callback($handle, $data);
            flock($handle, LOCK_UN);
            
            return true;
        }
        
        return false;
    }
    
    public function append($handle, $data)
    {
        while (($line = fgets($handle) !== false)) {
            continue;
        }
        
        fwrite($handle, $data . PHP_EOL);
    }
}
