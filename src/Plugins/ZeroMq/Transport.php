<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Tests\Core\Transport\Socket\SocketWrapperTest;
use Aztech\Events\Event;

class Transport implements \Aztech\Events\Transport
{

    private $pushSocket;

    private $pullSocket;

    private $multicast = false;

    private $multicastSet = false;

    public function __construct(SocketWrapper $pushSocket, SocketWrapper $pullSocket, $multicast = false)
    {
        $this->pullSocket = $pullSocket;
        $this->pushSocket = $pushSocket;
        $this->multicast = $multicast;
    }

    public function __destruct()
    {
        $this->pushSocket = null;
        $this->pullSocket = null;
    }

    public function read()
    {
        if ($this->multicast && ! $this->multicastSet) {
            echo 'Setting multicast option' . PHP_EOL;
            $this->pullSocket->setSockOpt(\ZMQ::SOCKOPT_IDENTITY, 'A');
            $this->multicastSet = true;
        }

        $this->pullSocket->connectIfNecessary();

        echo 'Reading...' . PHP_EOL;

        if ($this->multicast) {
            $producerPoll = new \ZMQPoll();
            $producerPoll->add($this->pullSocket->getSocket(), \ZMQ::POLL_IN);
            $read = $write = array();

            try {
                $producerEvents = $producerPoll->poll($read, $write, 5000);
            } catch (\ZMQPollException $e)
            {
                echo $e->getMessage();
            }

            if (! $producerEvents) {
                echo 'No producer events' . PHP_EOL;
                return;
            }
        }

        $data = $this->pullSocket->recv();

        if (isset($envelope)) {
            echo 'Read ' . $envelope . ' : ' . $data . PHP_EOL;
        }
        else {
            echo microtime(true) . ' Read ' . $data . PHP_EOL;
        }

        return $data;
    }

    public function write(Event $event, $serializedEvent)
    {
        $this->pushSocket->connectIfNecessary();

        echo 'Sending ' . $serializedEvent . PHP_EOL;

        if ($this->multicast) {
            $this->pushSocket->send("A", \ZMQ::MODE_SNDMORE);
        }

        $this->pushSocket->send($serializedEvent);

        echo 'Sent' . PHP_EOL;
    }

}
