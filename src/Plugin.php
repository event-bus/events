<?php

namespace Aztech\Events;

interface Plugin
{

    /**
     * @boolean
     */
    function canPublish();

    /**
     * @boolean
     */
    function canProcess();

    /**
     *
     * @return boolean
     */
    function hasTransport();

    /**
     *
     * @return \Aztech\Events\Transport
     */
    function getTransport();

    /**
     *
     * @return boolean
     */
    function hasFactory();

    /**
     *
     * @return \Aztech\Events\Factory
     */
    function getFactory();
}
