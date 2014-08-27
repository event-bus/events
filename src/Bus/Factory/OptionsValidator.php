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
            if (! array_key_exists($key, $options) && ! array_key_exists($key, $defaults)) {
                throw new \InvalidArgumentException('Options key ' . $key . ' is required in config.');
            }
            elseif (! array_key_exists($key, $options)) {
                $value = $defaults[$key];
            }
            else {
                $value = $options[$key];
            }

            $actual[$key] = $value;
        }

        return $actual;
    }

}
