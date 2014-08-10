<?php

namespace Evaneos\Events\Publishers\Stomp;

use Evaneos\Events\EventPublisher;
use Ratchet\Wamp\WampServerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Evaneos\Events\EventSerializer;
use Evaneos\Events\CategoryMatcher;
use Evaneos\Events\CategorySubscription;
use Evaneos\Events\EventSubscriber;

class EventPublisher implements EventPublisher, WampServerInterface, LoggerAwareInterface
{

    private $logger;

    private $serializer;

    private $subscribedTopics = array();

    private $categoryMatcher;

    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->logger = new NullLogger();
        $this->categoryMatcher = new CategoryMatcher();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function publish(Event $event)
    {
        foreach ($this->subscribedTopics as $name => $subscription) {
            /* @var $subscription CategorySubscription */
            if ($subscription->matches($event->getCategory())) {
                $subscription->getSubscriber()->handle($event);
            }
        }
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        if (array_key_exists($topic->getId(), $this->subscribedTopics)) {
            return;
        }
        
        $this->logger->debug(sprintf('Registering topic subscription : "%s".', $topic->getId()));
        
        $topicSub = new TopicSubscriber($topic, $this->serializer);
        $this->subscribedTopics[$topic->getId()] = new CategorySubscription($topic->getId(), $topicSub);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {}

    public function onOpen(ConnectionInterface $conn)
    {
        $this->logger->debug('Got new connection !');
    }

    public function onClose(ConnectionInterface $conn)
    {}

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        if ($topic != 'auth.register') {
            $conn->callError($id, $topic, 'Invalid call detected.')->close();
        }
        
        $token = $params[0];
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->logger->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
    }
}
