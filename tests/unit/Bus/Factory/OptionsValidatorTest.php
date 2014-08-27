<?php

namespace Aztech\Events\Tests\Bus\Factory;

use Aztech\Events\Bus\Factory\GenericOptionsDescriptor;
use Aztech\Events\Bus\Factory\OptionsValidator;

class OptionsValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredOptionTriggersException()
    {
        $descriptor = new GenericOptionsDescriptor();
        $descriptor->addOption('test');

        $validator = new OptionsValidator();
        $validator->validate($descriptor, array());
    }

    public function testMissingNonRequiredOptionIsReplacedWithDefaultValue()
    {
        $descriptor = new GenericOptionsDescriptor();
        $descriptor->addOption('test', false, 'default');

        $validator = new OptionsValidator();
        $options = $validator->validate($descriptor, array());

        $this->assertEquals('default', $options['test']);
    }

    public function testNonRequiredOptionIsNotReplacedWhenValueIsProvided()
    {
        $descriptor = new GenericOptionsDescriptor();
        $descriptor->addOption('test', false, 'default');

        $validator = new OptionsValidator();
        $options = $validator->validate($descriptor, array('test' => 'other'));

        $this->assertEquals('other', $options['test']);
    }
}
