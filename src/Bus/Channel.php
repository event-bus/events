<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Channel\ChannelReader;
use Aztech\Events\Bus\Channel\ChannelWriter;

/**
 *
 * @author thibaud
 */
interface Channel
{

    /**
     *
     * @return bool true if a reader is available for the channel, false otherwise.
     */
    function canRead();

    /**
     * Returns the available channel reader.
     * @return ChannelReader
     * @throws \BadMethodCallException when no reader is available, as indicated by the return value of canRead().
     */
    function getReader();

    /**
     *
     * @return bool true if a writer is available for the channel, false otherwise.
     */
    function canWrite();

    /**
     * Returns the available channel writer.
     * @return ChannelWriter
     * @throws \BadMethodCallException when no writer is available, as indicated by the return value of canWrite().
     */
    function getWriter();
}
