<?php

namespace Aztech\Events\Util\TrieMatcher;

class Word implements TrieMatcher
{

    private $word;

    public function __construct($word)
    {
        $this->word = $word;
    }

    function matches($component)
    {
        //echo 'Comparing ' . $component . ' against ' . $this->word . PHP_EOL;
        return ($this->word == $component);
    }
}
