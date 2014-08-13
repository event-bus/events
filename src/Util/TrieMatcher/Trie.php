<?php

namespace Aztech\Events\Util\TrieMatcher;

/**
 * Pseudo Trie tree implementation. It differs from a Trie tree in that it only holds one possible pattern (thus one branch), and uses
 * the DFA pattern used by RabbitMQ to evaluate wildcard filters.
 * Matching a category to a pattern is performed by walking the category components (separated by dots) and evaluating
 * each component against its counterpart in the pattern component chain. If a pattern component is *, the category counterpart
 * evaluates to true. If a pattern component is #, it acts as though zero or more '*' components are present in the pattern
 * at that point.
 *
 * More info here : http://www.rabbitmq.com/blog/2010/09/14/very-fast-and-scalable-topic-routing-part-1/
 * @author thibaud
 *
 */
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
            //echo 'Current pattern is {0,*} components, looking back at subvalue ' . $value . PHP_EOL;
            return $this->matches($value);
        }

        return false;
    }
}


