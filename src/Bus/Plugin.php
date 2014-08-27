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
    function hasChannel();

    /**
     *
     * @return \Aztech\Events\Bus\Channel
     */
    function getChannel();

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
