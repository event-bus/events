<?php

namespace Aztech\Events\Util\TrieMatcher;

class AnyWord implements TrieMatcher
{

    function matches($component)
    {
        //echo 'Comparing component against wildcard * : ' . $component . PHP_EOL;
        return $component != '';
    }
}
