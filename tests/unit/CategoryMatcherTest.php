<?php

namespace Evaneos\Events\Tests;

use Evaneos\Events\CategoryMatcher;

class CategoryMatcherTest extends \PHPUnit_Framework_TestCase
{

    private $truthTable = array(
        // Each item is 'categoryName', 'appliedFilter', 'expectedResuls'

        // Single word - words
        array('category', 'category', true),
        array('category', 'other', false),
        // Single word - wildcards
        array('category', '*', true),
        array('category', '#', true),
        // Two levels - words
        array('cat.sub', 'cat.sub', true),
        array('cat.sub', 'category', false),
        array('cat.sub', 'cat', false),
        // Two levels - wildcards
        array('cat.sub', 'cat.*', true),
        array('cat.sub', '*.sub', true),
        array('cat.sub', '*.*', true),
        array('cat.sub', '#', true),
        array('cat.sub', '#.#', true),
        array('cat.sub', '#.sub', true),
        array('cat.sub', '#.other', false),
        array('cat.sub', 'other.#', false),
        array('cat.sub', 'cat.#', true),
        array('cat.sub', 'sub.*', false),
        array('cat.sub', '*.sub.*', false),
        array('cat.sub', '*.*.*', false),
        // Three levels
        array('cat.sub.test', '*.*.*', true),
        array('cat.sub.test', '#', true),
        array('cat.sub.test', 'cat.#', true),
        array('cat', 'cat.#', true),
        array('cat.sub.test', '#.mismatch', false),
        array('cat.sub.test', '#.test', true),
        array('cat.sub.test', 'cat.#.test', true),
        array('cat.a.s.test', 'cat.#.test', true),
        array('cat.a.s.test', 'cat.#.mismatch', false),
        array('cat.a.s.test', '#.a.#', true),
        array('cat.a.s.test', '#.s.#', true),
        array('cat.a.s.test', '*.#.s', false),
        array('cat.a.s.test', '#.mismatch', false),
        array('cat.a.s.test', '#.mismatch.#', false),
    );

    public function getTruthTable()
    {
        return $this->truthTable;
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
