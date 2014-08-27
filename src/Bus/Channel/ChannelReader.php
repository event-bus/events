<?php

namespace Aztech\Events\Bus\Channel;

interface ChannelReader
{

    /**
     * @desc Each call to read should return the next available event in its serialized form, if any. If no events are available,
     * implementations are allowed to either block until an event is available, or return null.
     * <br /><br />
     * This method must return a serialized event that will be deserialized by a Serializer instance.
     *
     * @return string
     */
    function read();
}
