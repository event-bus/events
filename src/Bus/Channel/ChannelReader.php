<?php

namespace Aztech\Events\Bus\Channel;

interface ChannelReader
{

    /**
     * Each call to read should return the next available event in its serialized form, if any. If no events are available, implementations are allowed to either block until an event is available, or return null.
     * This method must return a serialized event that will be deserialized by a Serializer instance.
     *
     * @return string
     */
    function read();
}
