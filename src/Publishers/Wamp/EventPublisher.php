<?php

namespace Evaneos\Events\Publishers\Wamp;

use Evaneos\Events\EventPublisher;
use Ratchet\Wamp\WampServerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Evaneos\Events\EventSerializer;
use Evaneos\Events\CategoryMatcher;
use Evaneos\Events\CategorySubscription;
use Evaneos\Events\EventSubscriber;
use Evaneos\Events\SimpleDispatcher;
use Evaneos\Events\SimpleEvent;
use Evaneos\Events\Subscribers\CallbackSubscriber;

class EventPublisher implements EventPublisher, WampServerInterface, LoggerAwareInterface
{

    private $logger;

    private $serializer;

    private $subscribedTopics = array();

    private $categoryMatcher;

    private $internalDispatcher;

    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->logger = new NullLogger();
        $this->categoryMatcher = new CategoryMatcher();
        $this->internalDispatcher = new SimpleDispatcher();
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

    public function on($event, $callback)
    {
        $this->internalDispatcher->addListener($event, new CallbackSubscriber($callback));
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        if (array_key_exists($topic->getId(), $this->subscribedTopics)) {
            return;
        }

        $this->logger->debug(sprintf('Registering topic subscription : "%s".', $topic->getId()));

        $topicSub = new TopicSubscriber($topic, $this->serializer);
        $this->subscribedTopics[$topic->getId()] = new CategorySubscription($topic->getId(), $topicSub);

        $event = new SimpleEvent('subscribe', array(
            'connection' => $conn,
            'topic' => $topic
        ));

        $this->internalDispatcher->dispatch($event);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        $event = new SimpleEvent('unsubscribe', array(
            'connection' => $conn,
            'topic' => $topic
        ));

        $this->internalDispatcher->dispatch($event);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $event = new SimpleEvent('open', array(
            'connection' => $conn
        ));

        $this->internalDispatcher->dispatch($event);
        $this->logger->debug('Got new connection !');
    }

    public function onClose(ConnectionInterface $conn)
    {
        $event = new SimpleEvent('close', array(
            'connection' => $conn
        ));

        $this->internalDispatcher->dispatch($event);
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $event = new SimpleEvent('call', array(
            'connection' => $conn,
            'id' => $id,
            'topic' => $topic,
            'params' => $params
        ));

        $this->internalDispatcher->dispatch($event);
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    public function onError(ConnectionInterface $conn,\Exception $e)
    {
        $event = new SimpleEvent('error', array(
            'connection' => $conn,
            'error' => $ex
        ));

        $this->internalDispatcher->dispatch($event);
        $this->logger->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
    }
}
