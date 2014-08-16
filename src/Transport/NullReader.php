<?php

namespace Aztech\Events\Transport;

class NullReader implements Reader
{

    function read()
    {
        return null;
    }
}
