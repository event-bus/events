<?php

namespace Aztech\Events\Bus\Factory;

interface OptionsDescriptor
{
    function getOptionKeys();

    function getOptionDefaults();
}
