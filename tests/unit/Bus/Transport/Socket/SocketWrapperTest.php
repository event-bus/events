<?php

namespace Aztech\Events\Bus\Channel\Socket
{
    class SocketBehavior
    {
        const SOCK_NAME = 'mock-socket';

        const SOCK_CLIENT_NAME = 'mock-client-socket';

        static $socketCount = 0;

        static $errorCode = 0;

        static $errorMessage = '';

        static $buffers = array();

        static $sockets = array();

        static $accepted = array();

        static $allClients = array();

        static function reset()
        {
            self::$socketCount = 0;
            self::$errorCode = 0;
            self::$errorMessage = '';
            self::$buffers = array();
            self::$sockets = array();
            self::$accepted = array();
            self::$allClients = array();
        }

        static function isSocket($socket) {
            return in_array($socket, self::$sockets) || in_array($socket, self::$allClients);
        }

        static function socket_create($domain, $type)
        {
            $socket = self::SOCK_NAME . '-' . ++self::$socketCount;
            self::$sockets[] = $socket;
            self::$accepted[$socket] = array();

            return $socket;
        }

        static function socket_accept($sock)
        {
            if (! self::isSocket($sock)) {
                return false;
            }

            $socket = self::SOCK_CLIENT_NAME . '-' . ++self::$socketCount;
            self::$accepted[$sock][] = $socket;
            self::$allClients[] = $socket;
            self::$buffers[$socket] = array();

            return $socket;
        }

        static function socket_write($socket, $buffer = null, $length = null)
        {
            if (! self::isSocket($socket)) {
                return false;
            }

            $writeBuf = ((int)$length && strlen($buffer) > $length) ? substr($buffer, 0, $length) : $buffer;

            foreach (self::$accepted[$socket] as $client) {
                self::$buffers[$client][] = $writeBuf;
            }

            return strlen($writeBuf);
        }

        static function socket_read($socket, $length, $type = null)
        {
            if (! self::isSocket($socket)) {
                return false;
            }

            if (self::$errorCode != 0) {
                return '';
            }

            return array_shift(self::$buffers[$socket]);
        }
    }

    function socket_create($domain, $type)
    {
        return SocketBehavior::socket_create($domain, $type);
    }

    function is_resource($var)
    {
        if (SocketBehavior::isSocket($var)) {
            return true;
        }

        return\is_resource($var);
    }

    function get_resource_type($var)
    {
        if (SocketBehavior::isSocket($var)) {
            return 'Socket';
        }

        return\get_resource_type($var);
    }

    function socket_listen($sock)
    {
        return true;
    }

    function socket_bind($sock, $host, $port)
    {
        return true;
    }

    function socket_accept($sock)
    {
        return SocketBehavior::socket_accept($sock);
    }

    function socket_write($socket, $buffer = null, $length = null)
    {
        return SocketBehavior::socket_write($socket, $buffer, $length);
    }

    function socket_read($socket, $length, $type = null)
    {
        return SocketBehavior::socket_read($socket, $length, $type);
    }

    function socket_last_error($socket = null)
    {
        return SocketBehavior::$errorCode;
    }

    function socket_clear_error($socket=null)
    {
        SocketBehavior::$errorCode = 0;
        SocketBehavior::$errorMessage = '';

        return true;
    }

    function socket_getpeername($socket=null, & $address, & $port)
    {
        if (SocketBehavior::isSocket($socket)) {
            $address = $socket . '-peer';
            $port = 0;

            return;
        }

        return \socket_getpeername($socket, $address, $port);
    }
}

namespace Aztech\Events\Tests\Bus\Channel\Socket
{

    use Aztech\Events\Bus\Channel\Socket\Wrapper;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Aztech\Events\Bus\Channel\Socket\SocketBehavior;

    class SocketWrapperTest extends \PHPUnit_Framework_TestCase
    {

        private $server;

        private $client;

        private $reader;

        private $writer;

        protected function setUp()
        {
            SocketBehavior::reset();

            $sock = \Aztech\Events\Bus\Channel\Socket\socket_create(AF_INET, SOCK_STREAM);

            // Bind the socket to an address/port
            if (! \Aztech\Events\Bus\Channel\Socket\socket_bind($sock, 'localhost', 0)) {
                throw new \RuntimeException('Could not bind to address');
            }

            // Start listening for connections
            \Aztech\Events\Bus\Channel\Socket\socket_listen($sock);

            // Accept incoming requests and handle them as child processes.
            $this->client = \Aztech\Events\Bus\Channel\Socket\socket_accept($sock);
            $this->server = $sock;

            $logger = new ConsoleLogger(new ConsoleOutput(ConsoleOutput::VERBOSITY_QUIET));

            $this->writer = new Wrapper($this->server, $logger);
            $this->reader = new Wrapper($this->client, $logger);
        }

        /**
         * @expectedException \InvalidArgumentException
         */
        public function testCannotCreateWrapperWithNonSocketResource()
        {
            $resource = fopen('php://temp', 'r');

            $wrapper = new Wrapper($resource);
        }

        /**
         * @expectedException \InvalidArgumentException
         */
        public function testCannotCreateWrapperWithNonResource()
        {
            $resource = new \stdClass();

            $wrapper = new Wrapper($resource);
        }

        public function testRead()
        {
            $this->writer->writeRaw('data');

            $this->assertEquals('data', $this->reader->readRaw());
        }

        public function testReadReturnsEmptyStringOnSocketError()
        {
            $this->writer->writeRaw('data');

            SocketBehavior::$errorCode = SOCKET_EREMOTEIO;

            $this->assertEmpty($this->reader->readRaw());
        }
    }
}
