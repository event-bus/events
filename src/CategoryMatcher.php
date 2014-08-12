<?php

namespace Evaneos\Events;

use Evaneos\Events\Util\Trie;
use Evaneos\Events\Util\PrintDecorator;
class CategoryMatcher
{

    public function checkMatch($pattern, $category)
    {
        $trie = new Trie($pattern);

        return $trie->matches($category);

        // First, do simple & stupid assertions to avoid doing expensive calc when possible
        if ($pattern == $category) {
            // Exact match
            return true;
        }
        elseif ($pattern != $category && !$this->hasWildcard($pattern)) {
            // No match & no wildcards in pattern : no possible match
            return false;
        }

        $filterParts = explode('.', $pattern);
        $categoryParts = explode('.', $category);

        if (count($filterParts) > count($categoryParts)) {
            // Filter has more components than actual category : no possible match
            return false;
        }

        $hasCatchAll = false;

        $categoryCount = count($categoryParts);
        $filterCount = count($filterParts);
        $filterIndex = 0;

        for ($i = 0; $i < $categoryCount; $i ++) {
            // If we got this far and found no mismatching parts, then it's NOT a match (filter has less components
            // than actual category)
            if ($filterCount <= $filterIndex) {
                return false;
            }

            $remainingCategoryElements = $categoryCount - ($i + 1);
            $remainingFilterElements = $filterCount - ($filterIndex + 1);
            $filterOffset = $i - $filterIndex;

            echo PHP_EOL;
            echo $category . ' against ' . $pattern . PHP_EOL;
            echo 'Current filter : ' . $filterParts[$filterIndex] . ', current cat : ' . $categoryParts[$i] . PHP_EOL;
            echo 'Cat count : ' . $categoryCount . ', index : ' . $i, ', filter index : ' . $filterIndex . PHP_EOL;
            echo 'Remaining cat : ' . $remainingCategoryElements . ', filter offset : ' . $filterOffset, PHP_EOL;

            if ($remainingCategoryElements == 0 && $filterIndex + 1 < $filterCount)
            {
                if (! $this->isWildcard($filterParts[$filterIndex + 1])) {
                    return false;
                }
            }

            if ($filterParts[$filterIndex] == '*') {
                echo 'Found wildcard *, inc index & continuing' . PHP_EOL;
                $filterIndex++;
                continue;
            }
            elseif ($filterParts[$filterIndex] == '#') {
                // Magic catch-all wildcard. If we got this far, previous components do match.
                // Are we evaluating the last component of the filter ?
                if ($filterIndex == $filterCount - 1) {
                    // If this is the last filter component, it matches as there are no more constraints to apply.
                    echo 'No more elements while evaluating wildcard #, we have a match' . PHP_EOL;
                    return true;
                }
                else {
                    if ($remainingCategoryElements == $filterIndex && $categoryParts[$i] != $filterParts[$filterIndex + 1]) {
                        $filterIndex++;
                    }

                    echo 'Found wildcard #, continuing' . PHP_EOL;
                    continue;
                }
            }
            elseif ($filterParts[$filterIndex] != $categoryParts[$i]) {
                echo 'Word mismatch ' . $filterParts[$filterIndex] . ' vs ' . $categoryParts[$i] . ', returning false' . PHP_EOL;
                return false;
            }

            $filterIndex++;
        }

        echo 'Assuming match' . PHP_EOL;
        return true;
    }

    private function hasWildcard($pattern)
    {
        return substr_count($pattern, '*') > 0 || substr_count($pattern, '#') > 0;
    }

    private function isWildcard($pattern)
    {
        return $pattern == '*' || $pattern == '#';
    }
}
