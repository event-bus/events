<?php

namespace Aztech\Events\Util\TrieMatcher;

class AnyOrZeroWords implements TrieMatcher
{

    private $loopNode;

    public function __construct(TrieMatcher $loopNode)
    {
        $this->loopNode = $loopNode;
    }

    function matches($component)
    {
        //echo 'Look back test for ' . $component . PHP_EOL;
        $parts = explode('.', $component);

        if (! isset($parts[1])) {
            //echo 'No parts found, look back returns true' . PHP_EOL;
            return true;
        }

        //echo 'Looking back for ' . $parts[1] . PHP_EOL;
        return ($this->loopNode->matches($parts[1]));
    }
}
