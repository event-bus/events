<?php

namespace Aztech\Events\Bus\Plugins\Mixpanel;

use Aztech\Events\Bus\Channel\ChannelWriter;

class MixpanelChannelWriter implements ChannelWriter
{
    private $mixpanel;

    public function __construct(\Mixpanel $mixpanel)
    {
        $this->mixpanel = $mixpanel;
    }

    public function write(Event $event, $serializedEvent)
    {
        $properties = array();

        if ($event instanceof AbstractEvent) {
            $properties = $event->getProperties();
        }

        $this->mixpanel->track($event->getCategory(), $properties);
    }
}
