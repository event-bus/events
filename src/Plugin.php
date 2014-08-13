<?php

namespace Aztech\Events;

interface Plugin
{

    /**
     * @boolean
     * @return boolean
     */
    function canPublish();

    /**
     * @boolean
     * @return boolean
     */
    function canProcess();

    /**
     * @return boolean
     */
    function hasTransport();

    /**
     * @return \Aztech\Events\Transport
     */
    function getTransport();

    /**
     * @return boolean
     */
    function hasFactory();

    /**
     * @return \Aztech\Events\Factory
     */
    function getFactory();

}
