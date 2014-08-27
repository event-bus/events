<?php

namespace Aztech\Events\Bus;

/**
 *
 * @todo Segregate on and consume* methods to two separate interfaces
 * @author thibaud
 */
interface Consumer
{

    /**
     * @param string $filter Category filter expression
     * @param Subscriber|callable $subscriber A callback or a Subscriber to receive events.
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
