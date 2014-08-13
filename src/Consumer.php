<?php

namespace Aztech\Events;

/**
 * @todo Segregate on and consume* methods to two separate interfaces
 * @author thibaud
 *
 */
interface Consumer
{

    function on($filter, $subscriber);

    function consumeAll();

    function consumeNext();

}
