<?php

namespace Aztech\Events\Bus\Plugins\Mixpanel;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class MixpanelOptionsDescriptor implements OptionsDescriptor
{

    public function getOptionKeys()
    {
        return array(
            'project-token',
            'always-flush'
        );
    }

    public function getOptionDefaults()
    {
        return array('always-flush' => false);
    }
}
