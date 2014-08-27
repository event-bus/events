<?php

namespace Aztech\Events\Bus\Factory;

class NullOptionsDescriptor implements OptionsDescriptor
{
    public function getOptionDefaults()
    {
        return array();
    }

    function getOptionKeys()
    {
        return array();
    }
}
