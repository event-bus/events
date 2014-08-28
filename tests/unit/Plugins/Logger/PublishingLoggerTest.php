<?php

namespace Aztech\Events\Tests\Bus\Plugins\Logger;

use Aztech\Events\Bus\Plugins\Logger\PublishingLogger;
class PublishingLoggerTest extends \PHPUnit_Framework_TestCase
{

    public function testLogEntriesArePublishedAsEvents()
    {
        $publisher = $this->getMock('\Aztech\Events\Bus\Publisher');
        $publisher->expects($this->once())
            ->method('publish');

        $logger = new PublishingLogger($publisher);

        $logger->info('test');
    }
}
