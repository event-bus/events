<?php

namespace Aztech\Events\Bus\Transport;

class NullReader implements Reader
{

    function read()
    {
        return null;
    }
}
