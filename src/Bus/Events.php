<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Factory\StandardFactory;
use Aztech\Events\Bus\MemoryChannel;
use Aztech\Events\Bus\Serializer\NativeSerializer;
use Psr\Log\LoggerInterface;
use Aztech\Events\Bus\Channel\ChannelProvider;
use Psr\Log\NullLogger;
use Aztech\Events\EventDispatcher;
use Aztech\Events\Bus\Channel\ChannelProcessor;

/**
 * Facade-like class providing easy access to event factories.
 * @author thibaud
 */
class Events
{

    const NUM_ERR_NAME_REQUIRED = 100;

    const FMT_ERR_NAME_REQUIRED = 'Plugin key is required.';

    const NUM_ERR_NAME_REGISTERED = 101;

    const FMT_ERR_NAME_REGISTERED = 'Plugin key is already registered.';

    const NUM_ERR_PLUGIN_REGISTERED = 102;

    const FMT_ERR_PLUGIN_REGISTERED = 'Plugin key is already registered.';

    const NUM_ERR_NO_PROVIDERS = 103;

    const FMT_ERR_NO_PROVIDERS = 'Plugin needs to provide at least a transport or a factory.';

    const NUM_ERR_NO_FEATURES = 104;

    const FMT_ERR_NO_FEATURES = 'Plugin provides no publish or process features (at least one is required).';

    const NUM_ERR_UNKNOWN_PLUGIN = 105;

    const FMT_ERR_UNKNOWN_PLUGIN = 'Plugin "%s" is not registered.';

    const NUM_ERR_UNSUPPORTED_FEATURE = 106;

    const FMT_ERR_UNSUPPORTED_FEATURE = 'Feature not supported by plugin "%".';

    const NUM_ERR_INVALID_PLUGIN = 107;

    const FMT_ERR_INVALID_PLUGIN = 'Plugin must be an instance of PluginFactory or Factory';

    /**
     *
     * @var PluginFactory[]
     */
    private static $plugins = array();

    /**
     *
     * @var Factory[]
     */
    private static $factories = array();

    public static function reset()
    {
        self::$plugins = array();
    }

    /**
     * Register a new plugin to provide new publish/subscribe methods.
     *
     * @param string $name A non-empty identifier for the plugin.
     * @param PluginFactory $plugin The plugin factory to register.
     * @throws \InvalidArgumentException when plugin or plugin name is already registered, or when name is invalid (empty).
     */
    public static function addPlugin($name, PluginFactory $plugin)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException(self::FMT_ERR_NAME_REQUIRED, self::NUM_ERR_NAME_REQUIRED);
        }

        if (array_key_exists($name, self::$plugins)) {
            throw new \InvalidArgumentException(self::FMT_ERR_NAME_REGISTERED, self::NUM_ERR_NAME_REGISTERED);
        }

        if (in_array($plugin, self::$plugins, true)) {
            throw new \InvalidArgumentException(self::FMT_ERR_PLUGIN_REGISTERED, self::NUM_ERR_PLUGIN_REGISTERED);
        }

        self::$plugins[$name] = $plugin;
    }

    /**
     * Fetches a plugin by its name.
     *
     * @param string $name Name of the plugin to get.
     * @return PluginFactory
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function getPlugin($name)
    {
        if (! array_key_exists($name, self::$plugins)) {
            throw new \OutOfBoundsException(sprintf(self::FMT_ERR_UNKNOWN_PLUGIN, self::NUM_ERR_UNKNOWN_PLUGIN));
        }

        return self::$plugins[$name];
    }

    /**
     * Creates a new standard event.
     *
     * @param string $name Category of the event.
     * @param array $properties An indexed array where keys are property names and values are matching property values.
     * @return \Aztech\Events\Bus\Event
     */
    public static function create($name, array $properties = array())
    {
        return new Bus\Event($name, $properties);
    }

    /**
     * Creates a new factory. Should fit most cases.
     *
     * @param PluginFactory the plugin factory from which the actual factory will be built.
     * @param Serializer $serializer Serializer used for serializing the emitted events. Defaults to a new NativeSerializer instance.
     * @param LoggerInterface $logger A logger instance or null.
     * @return \Aztech\Events\Factory
     */
    public static function createFactory(PluginFactory $plugin, Serializer $serializer = null, LoggerInterface $logger = null)
    {
        $serializer = $serializer ?  : new NativeSerializer();

        $factory = new GenericFactory($serializer, $plugin->getChannelProvider(), $plugin->getOptionsDescriptor());
        $factory->setLogger($logger ?  : new NullLogger());

        return $factory;
    }

    /**
     * Creates a new event consumer.
     *
     * @param string $name Name of the plugin to use to create the consumer
     * @param array $options Options to pass to the factory.
     * @return \Aztech\Events\Bus\Consumer
     * @throws \BadMethodCallException when the selected plugin does not support consumers.
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createApplication($name, array $options = array(), array $bindings = array())
    {
        $factory = self::getFactory($name);
        $application = new Application($factory->createProcessor($options), new EventDispatcher());

        return $application;
    }

    /**
     * Creates a new event publisher.
     *
     * @param string $name Name of the plugin to ues to create the publisher.
     * @param array $options Options to pass to the factory.
     * @return \Aztech\Events\Bus\Publisher
     * @throws \BadMethodCallException when the selected plugin does not support publishers.
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createPublisher($name, array $options = array())
    {
        $factory = self::getFactory($name);

        return $factory->createPublisher($options);
    }

    /**
     * Creates a new event publisher.
     *
     * @param string $name Name of the plugin to ues to create the publisher.
     * @param array $options Options to pass to the factory.
     * @return \Aztech\Events\Bus\Publisher
     * @throws \BadMethodCallException when the selected plugin does not support publishers.
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createProcessor($name, array $options = array())
    {
        $factory = self::getFactory($name);

        return new Application($factory->createProcessor($options), new EventDispatcher());
    }

    /**
     * Helper to throw an unsupported feature exception.
     *
     * @param string $name Name of the concerned plugin.
     * @throws \BadMethodCallException
     */
    private static function throwUnsupportedFeatureException($name)
    {
        $message = sprintf(self::FMT_ERR_UNSUPPORTED_FEATURE, $name);
        $code = self::NUM_ERR_UNSUPPORTED_FEATURE;

        throw new \BadMethodCallException($message, $code);
    }

    /**
     *
     * @param string $name
     * @return \Aztech\Events\Bus\Factory
     */
    private static function getFactory($name)
    {
        $pluginFactory = self::getPlugin($name);

        if (! array_key_exists($name, self::$factories)) {
            self::$factories[$name] = self::createFactory($pluginFactory);
        }

        return self::$factories[$name];
    }
}
