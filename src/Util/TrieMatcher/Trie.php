<?php

namespace Evaneos\Events\Util\TrieMatcher;

class Trie implements TrieMatcher
{

    private $selfTrie = null;

    private $next = null;

    public function __construct($pattern)
    {
        $this->parse($pattern);
    }

    private function parse($pattern)
    {
        //echo 'Parsing ' . $pattern . PHP_EOL;

        if (substr_count($pattern, '.') == 0) {
            $this->pattern = $this->getNodeFor($pattern);
            $this->next = null;
        }
        else {
            list($key, $value) = explode('.', $pattern, 2);

            $this->pattern = $this->getNodeFor($key, false);
            $this->next = new self($value);
        }
    }

    private function getNodeFor($word, $noWrap = true)
    {
        if ($word == '*') {
            return new AnyWord();
        }
        elseif ($word == '#') {
            return new AnyOrZeroWords($this);
        }
        else {
            return new Word($word);
        }
    }

    function matches($component)
    {
        //echo 'Testing ' . $component . PHP_EOL;

        $parts = explode('.', $component, 2);
        $key = $parts[0];
        $value = isset($parts[1]) ? $parts[1] : null;

        if (! $this->pattern->matches($key)) {
            //echo 'First component ' . $key . ' does not match key pattern.' . PHP_EOL;
            return false;
        }

        if (! $this->next) {
            if (! $value) {
                //echo 'No sub components and no next node, match.' . PHP_EOL;
                return true;
            }
        }
        elseif ($this->next->matches($value)) {
            return true;
        }

        if ($this->pattern instanceof AnyOrZeroWords && $value) {
            return $this->matches($value);
        }

        return false;
    }
}


