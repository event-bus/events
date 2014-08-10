<?php

namespace Evaneos\Events;

class CategoryMatcher
{

    public function checkMatch($pattern, $category)
    {
        $filterParts = explode('.', $pattern);
        $categoryParts = explode('.', $category);
        
        if (count($filterParts) > count($categoryParts)) {
            return false;
        }
        
        $categoryCount = count($categoryParts);
        $filterCount = count($filterParts);
        
        for ($i = 0; $i < $categoryCount; $i ++) {
            // If we got this far and found no mismatching parts, then it's a match.
            if ($filterCount <= $i) {
                break;
            }
            
            if ($filterParts[$i] == '*') {
                continue;
            }
            elseif ($filterParts[$i] != $categoryParts[$i]) {
                return false;
            }
        }
        
        return true;
    }
}
