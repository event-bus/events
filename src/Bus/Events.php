<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Factory\StandardFactory;
use Aztech\Events\Bus\MemoryTransport;
use Aztech\Events\Bus\Serializer\NativeSerializer;
use Psr\Log\LoggerInterface;

/**
 * Facade-like class providing easy access to event factories.
 * @author thibaud
 */
class Events
{

    const NUM_ERR_NAME_REQUIRED = 100;
    const FMT_ERR_NAME_REQUIRED = 'Plugin name is required.';

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

    private static $plugins = array();

    public static function reset()
    {
        self::$plugins = array();
    }

    /**
     * Register a new plugin to provide new publish/subscribe methods.
     * @param string $name A non-empty identifier for the plugin.
     * @param Plugin $plugin The plugin to register.
     * @throws \InvalidArgumentException when plugin or plugin name is already registered, or when name is invalid (empty).
     */
    public static function addPlugin($name, Plugin $plugin)
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

        self::validatePluginFeatures($plugin);

        self::$plugins[$name] = $plugin;
    }

    private static function validatePluginFeatures(Plugin $plugin)
    {
        if (! $plugin->hasFactory() && ! $plugin->hasTransport()) {
            throw new \InvalidArgumentException(self::FMT_ERR_NO_PROVIDERS, self::NUM_ERR_NO_PROVIDERS);
        }

        if (! $plugin->canProcess() && ! $plugin->canPublish()) {
            throw new \InvalidArgumentException(self::FMT_ERR_NO_FEATURES, self::NUM_ERR_NO_FEATURES);
        }
    }

    /**
     * Creates a new standard event.
     * @param string $name Category of the event.
     * @param array $properties An indexed array where keys are property names and values are matching property values.
     * @return \Aztech\Events\Bus\Event
     */
    public static function create($name, array $properties = array())
    {
        return new Bus\Event($name, $properties);
    }

    /**
     * Creates a new transport-based factory. Should fit most cases not covered by plugins, and allows for very small plugins :
     * the minimum they need to implement is a Transport class.
     * @param Transport $transport Transport used for emitting events. Defaults to a new MemoryTransport instance.
     * @param Serializer $serializer Serializer used for serializing the emitted events. Defaults to a new NativeSerializer instance.
     * @return \Aztech\Events\Factory
     */
    public static function createFactory(Transport $transport, Serializer $serializer = null, LoggerInterface $logger = null)
    {
        $factory = new StandardFactory($transport, $serializer ?: new NativeSerializer(), $logger);

        return $factory;
    }

    /**
     * Creates a new event consumer.
     * @param string $name Name of the plugin to use to create the consumer
     * @param array $options Options to pass to the factory.
     * @return \Aztech\Events\Bus\Consumer
     * @throws \BadMethodCallException when the selected plugin does not support consumers.
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createConsumer($name, array $options = array())
    {
        $plugin = self::getPlugin($name);
        $factory = self::getPluginFactory($name);

        if ($plugin->canProcess()) {
            return $factory->createConsumer($options);
        }

        self::throwUnsupportedFeatureException($name);
    }

    /**
     * Creates a new event publisher.
     * @param string $name Name of the plugin to ues to create the publisher.
     * @param array $options Options to pass to the factory.
     * @return \Aztech\Events\Bus\Publisher
     * @throws \BadMethodCallException when the selected plugin does not support publishers.
     * @throws \OutOfBoundsException when the plugin name is not registered.
     */
    public static function createPublisher($name, array $options = array())
    {
        $plugin = self::getPlugin($name);
        $factory = self::getPluginFactory($name);

        if ($plugin->canPublish()) {
            return $factory->createPublisher($options);
        }

        self::throwUnsupportedFeatureException($name);
    }

    /**
     * Fetches a plugin by its name.
     * @param string $name Name of the plugin to get.
     * @return \Aztech\Events\Bus\Plugin
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
     * Gets the factory from a plugin or creates a new one from the plugin's transport if the
     * plugin does not provide a factory.
     * @see \Aztech\Events\Events::createFactory() method to create a factory from a transport without plugin.
     * @param string $name Name of the plugin.
     * @throws \BadMethodCallException when the plugin provides no factory or transport.
     * @throws \OutOfBoundsException when the plugin name is not registered.
     * @return \Aztech\Events\Factory
     */
    public static function getPluginFactory($name)
    {
        $plugin = self::getPlugin($name);

        if (! $plugin->hasFactory() && $plugin->hasTransport()) {
            $factory = self::createFactory($plugin->getTransport());

            if (! $plugin->canProcess()) {
                $factory->disableProcess();
            }

            if (! $plugin->canPublish()) {
                $factory->disablePublish();
            }
        }
        elseif ($plugin->hasFactory()) {
            $factory = $plugin->getFactory();
        }
        else {
            // Should never happen (guarded in addPlugin method), but you never know...
            throw new \BadMethodCallException(self::FMT_ERR_NO_PROVIDERS, self::NUM_ERR_NO_PROVIDERS);
        }

        return $factory;
    }

    /**
     * Helper to throw an unsupported feature exception.
     * @param string $name Name of the concerned plugin.
     * @throws \BadMethodCallException
     */
    private static function throwUnsupportedFeatureException($name)
    {
        $message = sprintf(self::FMT_ERR_UNSUPPORTED_FEATURE, $name);
        $code = self::NUM_ERR_UNSUPPORTED_FEATURE;

        throw new \BadMethodCallException($message, $code);
    }
}
