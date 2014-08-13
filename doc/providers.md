# Event providers

The core of the library is made up of interfaces, without implementations.

Each provider available in the `Providers` sub-directory provides implementations for those interfaces.

Listed here are all the providers that the library natively provides.

**By default, the dependencies for each provider are not required in the library's Composer file. YOU NEED TO
REQUIRE VIA COMPOSER THE PROVIDER'S DEPENDENCIES WHEN NEEDED.**

## Simple provider

### Requires

*No dependencies*

### Supported elements

  * Publish
  * Dispatch
  
### Initialization

```php

include __DIR__ . '/vendor/autoload.php';

$factory = \Evaneos\Events\Events::createSimpleFactory();
$publisher = $factory->createPublisher();

// ...
```

## AMQP provider

### Requires

  * videlalvaro/php-amqplib : ~2

### Supported elements :

  * Publish
  * Dispatch

### Initialization

```php

include __DIR__ . '/vendor/autoload.php';

$factory = \Evaneos\Events\Events::createAmqpFactory();
$publisher = $factory->createPublisher();

// ...
```

## Wamp provider

### Requires

  * cboden/Ratchet : dev-master

### Supported elements :

  * Publish

### Initialization

```php

include __DIR__ . '/vendor/autoload.php';

$factory = \Evaneos\Events\Events::createWampFactory();
$publisher = $factory->createPublisher();

// ...
```

## Stomp provider

### Requires

  * fusesource/stomp-php : 2.0.*

### Supported elements :

  * Publish
  * Dispatch
  
### Initialization

```php

include __DIR__ . '/vendor/autoload.php';

$factory = \Evaneos\Events\Events::createStompFactory();
$publisher = $factory->createPublisher();

// ...
```

## PDO provider

### Requires

  * ext/mysql : To use the PDO provider with MySQL
  * ext/pgsql : To use the PDO provider with PostGres
  * ext/xxxx : Whatever PDO compatible extension depending on your database.

### Supported elements :

  * Publish
  * Dispatch

### Initialization

```php

include __DIR__ . '/vendor/autoload.php';

$factory = \Evaneos\Events\Events::createPdoFactory();
$publisher = $factory->createPublisher();

// ...
```

## Redis provider

### Requires

  * predis/predis : ~1

### Supported elements :

  * Publish
  * Dispatch
  
### Initialization

```php

include __DIR__ . '/vendor/autoload.php';

$factory = \Evaneos\Events\Events::createRedisFactory();
$publisher = $factory->createPublisher();

// ...
```