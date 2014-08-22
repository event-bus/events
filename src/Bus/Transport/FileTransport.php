<?php

namespace Aztech\Events\Bus\Transport;

use Aztech\Events\Event;
use Aztech\Events\Transport;
use Aztech\Util\File\Files;

class FileTransport implements Transport
{

    private $writer;
    
    private $file;

    public function __construct($file)
    {
        $this->writer = new FileWriter($file);
        $this->reader = new FileReader($file);
        
        $this->file = $file;

        if (! file_exists($this->file)) {
            file_put_contents($this->file, '');
        }
    }
    
    public function write(Event $event, $serializedData)
    {
        return $this->writer->write($event, $serializedData);
    }

    public function read()
    {
        return $this->reader->read();   
    }
}
