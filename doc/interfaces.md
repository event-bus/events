# Event interfaces

Here is the list of all the interfaces defined in the root namespace \Aztech\Events.

## \Aztech\Events

### Consumer
 
A consumer is a high-level event listener. You can register listeners to be notified of incoming events, and run a blocking loop to wait for new events.

### Dispatcher

A dispatcher is an in-process event emitter. Once again, you can register listeners to be notified of events using a category filter. Any events sent to the dispatcher via its `dispatch` method will be sent to listeners who accept the event.

### Event

The base event interface. An event must at least have an ID and a category. The rest is up to you. There is a standard Event implementation available in the `Aztech\Events\Core` namespace that can suit most simple needs.

### Factory

Factory interface defines a standard interface to build an event publisher and/or consumer. This allows to centralize building of publishers and consumers via the `Aztech\Events\Events` class.

### Plugin

A plugin is a descriptor for an event system. It can either provide a Factory instance or a Transport instance, depending on how publishers and consumers need to be implemented.

Most cases can do with implementing a Transport, which only needs to be able to queue serialized event data somewhere, and dequeue it later on.

### Processor

A processor consumes events from a queue or any datastore, and forwards them to an in-process dispatcher. It is meant to be used for out-of-process event processing.

A default implementation is available for use with Transport instances.

### Publisher

A publisher pushes events to a queue or any datastore. It is meant to be used for out-of-process event publishing.

A special case is the SynchronousPublisher, which pushes published message directly to a Dispatcher instance, making it in effect an in-process event publisher, and also implements Consumer to expose a common method to subscribe to events.

### Serializer

Serializers are used to convert Events to and from string representations. There are built-in implementations which provide native serialization, Json serialization, or Protobuf serialization.

### Subscriber

A subscriber is an interface for listeners that are bound to a Dispatcher. They can accept or reject an event on more specific criteria than categories, and if they accept it, process it.

### Transport

A transport is a simple class which provides read/write features to datastores that will be used as queues for events.