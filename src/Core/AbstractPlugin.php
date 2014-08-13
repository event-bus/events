<?php

namespace Aztech\Events\Core;

use Aztech\Events\Plugin;
use Aztech\Events\Transport;

abstract class AbstractPlugin implements Plugin
{

    private $canProcess = true;

    private $canPublish = true;

    private $factory = null;

    private $transport = null;

    protected function setProcessFlag($enabled)
    {
        $this->canProcess = $enabled;
    }

    protected function setPublishFlag($enabled)
    {
        $this->canPublish = $enabled;
    }

    protected function setTransport(Transport $transport)
    {
        $this->transport = $transport;
    }

    protected function setFactory(AbstractFactory $factory)
    {
        $this->factory = $factory;
    }

    function canProcess()
    {
        return $this->canProcess;
    }

    function canPublish()
    {
        return $this->canPublish;
    }

    function hasTransport()
    {
        return ($this->transport != null);
    }

    function getTransport()
    {
        return $this->transport;
    }

    function hasFactory()
    {
        return ($this->factory != null);
    }

    function getFactory()
    {
        return $this->factory;
    }
}
