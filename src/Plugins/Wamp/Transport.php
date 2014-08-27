<?php

namespace Aztech\Events\Bus\Plugins\Wamp;

use Aztech\Events\Bus\Event;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Transport implements \Aztech\Events\Bus\Transport, WampServerInterface, LoggerAwareInterface
{

    private $logger;

    private $subscribedTopics = array();

    public function __construct() {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function read()
    {
        throw new \NotSupportedException();
    }

    public function write(\Aztech\Events\Event $event, $serializedRepresentation)
    {
        foreach ($this->subscribedTopics as $name => $subscription) {
            /* @var $subscription CategorySubscription */
            if ($subscription->matches($event->getCategory())) {
                $pubEvent = new Event('publish', array('event' => $event, 'data' => $serializedRepresentation));

                $subscription->getSubscriber()->handle($pubEvent);
            }
        }
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        if (array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->logger->debug(sprintf('Topic "%s" already registered, ignoring.'));
            return;
        }

        $this->logger->debug(sprintf('Registering topic subscription : "%s".', $topic->getId()));

        $topicSub = new Subscriber($topic);
        $this->subscribedTopics[$topic->getId()] = new CategorySubscription($topic->getId(), $topicSub);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {

    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->logger->debug('Got new connection !');
    }

    public function onClose(ConnectionInterface $conn)
    {

    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $this->logger->notice('Received unauthorized call, dropping associated connection.');
        $conn->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $this->logger->notice('Received unauthorized publish, dropping associated connection.');
        $conn->close();
    }

    public function onError(ConnectionInterface $conn,\Exception $e)
    {
        $this->logger->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
    }
}
