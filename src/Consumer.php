<?php

namespace Aztech\Events;

/**
 *
 * @todo Segregate on and consume* methods to two separate interfaces
 * @author thibaud
 */
interface Consumer
{

    /**
     *
     * @return void
     */
    function on($filter, $subscriber);

    /**
     *
     * @return void
     */
    function consumeAll();

    /**
     *
     * @return void
     */
    function consumeNext();
}
