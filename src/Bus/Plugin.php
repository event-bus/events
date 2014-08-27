<?php

namespace Aztech\Events\Bus;

interface Plugin
{

    /**
     * @return boolean
     */
    function canPublish();

    /**
     * @return boolean
     */
    function canProcess();

    /**
     *
     * @return boolean
     */
    function hasTransport();

    /**
     *
     * @return \Aztech\Events\Bus\Transport
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
