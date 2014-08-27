<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Plugin;
use Aztech\Events\Bus\Transport;

class GenericPlugin implements Plugin
{

    private $canProcess = true;

    private $canPublish = true;

    private $factory = null;

    private $transport = null;

    /**
     * @param boolean $enabled
     */
    public function setProcessFlag($enabled)
    {
        $this->canProcess = $enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setPublishFlag($enabled)
    {
        $this->canPublish = $enabled;
    }

    public function setTransport(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function setFactory(Factory $factory)
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

    /**
     * (non-PHPdoc)
     * @see \Aztech\Events\Bus\Plugin::getFactory()
     */
    function getFactory()
    {
        return $this->factory;
    }
}
