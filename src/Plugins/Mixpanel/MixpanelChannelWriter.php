<?php

namespace Aztech\Events\Bus\Plugins\Mixpanel;

use Aztech\Events\Bus\Channel\ChannelWriter;
use Aztech\Events\Event;
use Aztech\Events\Bus\AbstractEvent;

class MixpanelChannelWriter implements ChannelWriter
{
    private $mixpanel;

    private $alwaysFlush = false;

    public function __construct(\Mixpanel $mixpanel, $alwaysFlush = false)
    {
        $this->mixpanel = $mixpanel;
        $this->alwaysFlush = $alwaysFlush;
    }

    public function write(Event $event, $serializedEvent)
    {
        $properties = array();

        if ($event instanceof AbstractEvent) {
            $properties = $event->getProperties();
        }

        $this->mixpanel->track($event->getCategory(), $properties);

        if ($this->alwaysFlush) {
            $this->mixpanel->flush();
        }
    }
}
