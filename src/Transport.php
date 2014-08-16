<?php

namespace Aztech\Events;

use Aztech\Events\Transport\Reader;
use Aztech\Events\Transport\Writer;

interface Transport extends Reader, Writer
{
        
}
