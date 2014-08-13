<?php

namespace Aztech\Events\Tests;

use Aztech\Events\Core\CategoryMatcher;

class CategoryMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function getTruthTable()
    {
        return CategoryMatchTruthTable::get();
    }

    /**
     * @dataProvider getTruthTable
     */
    public function testMatcherResultsAreConformToTruthTable($category, $filter, $expected)
    {
        $matcher = new CategoryMatcher();

        $this->assertEquals($expected, $matcher->checkMatch($filter, $category));
    }

}
