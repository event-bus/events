<?php

namespace Aztech\Events\Bus;

use Aztech\Events\EventDispatcher;
use Aztech\Events\Bus\Serializer\NativeSerializer;
use Psr\Log\LoggerInterface;

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

    const NUM_ERR_UNKNOWN_PLUGIN = 105;

    const FMT_ERR_UNKNOWN_PLUGIN = 'Plugin "%s" is not registered.';

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
     * @return Event
     */
    public static function create($name, array $properties = array())
    {
        return new Event($name, $properties);
    }

    /**
     * Creates a new factory. Should fit most cases.
     *
     * @param PluginFactory the plugin factory from which the actual factory will be built.
     * @param Serializer $serializer Serializer used for serializing the emitted events. Defaults to a new NativeSerializer instance.
     * @param LoggerInterface $logger A logger instance or null.
     * @return Factory
     */
    public static function createFactory(PluginFactory $plugin, Serializer $serializer = null)
    {
        $serializer = $serializer ?  : new NativeSerializer();
        $factory = new GenericFactory($serializer, $plugin->getChannelProvider(), $plugin->getOptionsDescriptor());

        return $factory;
    }

    /**
     * Creates a new application.
     *
     * @param string $name Name of the plugin to use to create the consumer
     * @param array $options Options to pass to the factory.
     * @return Application
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createApplication($name, array $options = array(), array $bindings = array())
    {
        $factory = self::getFactory($name);
        $application = new Application($factory->createProcessor($options), new EventDispatcher());

        foreach ($bindings as $filter => $subscriber) {
            $application->on($filter, $subscriber);
        }

        return $application;
    }

    /**
     * Creates a new event publisher.
     *
     * @param string $name Name of the plugin to ues to create the publisher.
     * @param array $options Options to pass to the factory.
     * @return Publisher
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createPublisher($name, array $options = array())
    {
        $factory = self::getFactory($name);

        return $factory->createPublisher($options);
    }

    /**
     * Creates a new event processor.
     *
     * @param string $name Name of the plugin to ues to create the processor.
     * @param array $options Options to pass to the factory.
     * @return Processor
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createProcessor($name, array $options = array())
    {
        $factory = self::getFactory($name);

        return new Application($factory->createProcessor($options), new EventDispatcher());
    }

    /**
     *
     * @param string $name
     * @return Factory
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
