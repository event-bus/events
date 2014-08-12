<?php

namespace Evaneos\Events\Util;

class Trie implements TrieNode
{

    private $pattern;

    private $next = null;

    public function __construct($pattern)
    {
        $this->parse($pattern);
    }

    private function parse($pattern)
    {
        echo 'Parsing ' . $pattern . PHP_EOL;

        if (substr_count($pattern, '.') == 0) {
            $this->pattern = $this->getNodeFor($pattern);
            $this->next = null;
        }
        else {
            list($key, $value) = explode('.', $pattern, 2);

            $this->pattern = $this->getNodeFor($key);
            $this->next = new Trie($value);
        }
    }

    private function getNodeFor($word)
    {
        if ($word == '*') {
            return new Any();
        }
        elseif ($word == '#') {
            return new LookBack($this);
        }
        else {
            return new Word($word);
        }
    }

    function matches($component)
    {
        echo 'Testing ' . $component . PHP_EOL;

        $parts = explode('.', $component, 2);
        $key = $parts[0];
        $value = isset($parts[1]) ? $parts[1] : null;

        if (! $this->pattern->matches($key)) {
            echo 'First component ' . $key . ' does not match key pattern.' . PHP_EOL;
            return false;
        }

        if (! $this->next) {
            if ($this->pattern instanceof LookBack && $value) {
                return $this->pattern->matches($value);
            }

            if (! $value) {
                echo 'No sub components and no next node, match.' . PHP_EOL;
                return true;
            }
            else {
                echo 'Sub components but no next node, no match.' . PHP_EOL;
                return false;
            }
        }

        if ($this->next) {
            return $this->next->matches($value);
        }

        return true;
    }
}

class Word implements TrieNode
{

    private $word;

    public function __construct($word)
    {
        $this->word = $word;
    }

    function matches($component)
    {
        echo 'Comparing ' . $component . ' against ' . $this->word . PHP_EOL;

        return ($this->word == $component);
    }
}

class LookBack implements TrieNode
{

    private $backNode;

    public function __construct(TrieNode $lookback)
    {
        $this->backNode = $lookback;
    }

    function matches($component)
    {
        echo 'Look back test for ' . $component . PHP_EOL;
        $parts = explode('.', $component);

        if (! isset($parts[1])) {
            echo 'No parts found, look back returns true' . PHP_EOL;
            return true;
        }

        echo 'Looking back for ' . $parts[1] . PHP_EOL;
        return ($this->backNode->matches($parts[1]));
    }
}

class Any implements TrieNode
{

    function matches($component)
    {
        echo 'Comparing component against wildcard * : ' . $component . PHP_EOL;

        return $component != '';
    }
}

interface TrieNode
{

    function matches($component);
}
