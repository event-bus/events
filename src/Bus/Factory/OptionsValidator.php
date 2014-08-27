<?php

namespace Aztech\Events\Bus\Factory;

class OptionsValidator
{

    public function validate(OptionsDescriptor $descriptor, array $options)
    {
        $keys = $descriptor->getOptionKeys();
        $defaults = $descriptor->getOptionDefaults();

        $actual = array();

        foreach ($keys as $key) {
            $this->validateKey($options, $defaults, $key);

            $actual[$key] = $this->getKeyValueOrDefault($options, $defaults, $key);
        }

        return $actual;
    }

    private function validateKey(array $options, array $defaults, $key)
    {
        if (! array_key_exists($key, $options) && ! array_key_exists($key, $defaults)) {
            throw new \InvalidArgumentException('Options key ' . $key . ' is required in config.');
        }
    }

    private function getKeyValueOrDefault(array $options, array $defaults, $key)
    {
        if (! array_key_exists($key, $options)) {
            $value = $defaults[$key];
        }
        else {
            $value = $options[$key];
        }

        return $value;
    }
}
