<?php

namespace Aztech\Events\Tests\Bus\Plugins\Amqp;

use Aztech\Events\Bus\Plugins\Amqp\CategoryPrefixHelper;
class CategoryPrefixHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPrefixedCategoryDoesNotModifyCategoryWithEmptyPrefix()
    {
        $helper = new CategoryPrefixHelper();

        $category = 'test';

        $this->assertEquals($category, $helper->getPrefixedCategory($category));
    }

    public function testGetPrefixedCategoryPrefixesCategoryWithNonEmptyPrefix()
    {
        $helper = new CategoryPrefixHelper('events');

        $this->assertEquals('events.test', $helper->getPrefixedCategory('test'));
    }
}
