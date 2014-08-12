<?php

namespace Evaneos\Events\Tests;

use Evaneos\Events\CategoryMatcher;

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
