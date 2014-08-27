<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

class CategoryPrefixHelper
{
    private $prefix = '';

    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
    }

    public function getPrefixedCategory($category)
    {
        if (! empty($this->prefix)) {
            $category = $this->prefix . '.' . $category;
        }

        return $category;
    }
}
