<?php

namespace Aztech\Events\Bus\Plugins\Mixpanel;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Channel\WriteOnlyChannel;

class MixpanelChannelProvider implements ChannelProvider
{

    public function createChannel(array $options = array())
    {
        $mixpanel = \Mixpanel::getInstance($options['project-token']);
        $writer = new MixpanelChannelWriter($mixpanel, $options['always-flush']);

        return new WriteOnlyChannel($writer);
    }
}
