<?php

namespace Aztech\Events\Core;

use Aztech\Events\Util\TrieMatcher\Trie;

class CategoryMatcher
{

    public function checkMatch($pattern, $category)
    {
        $trie = new Trie($pattern);

        return $trie->matches($category);
    }
}
