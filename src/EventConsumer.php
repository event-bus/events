<?php

namespace Evaneos\Events;

interface EventConsumer
{

    function on($filter, $subscriber);

    function consumeAll();

    function consumeNext();

}
