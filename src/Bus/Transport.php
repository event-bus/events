<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Transport\Reader;
use Aztech\Events\Bus\Transport\Writer;

interface Transport extends Reader, Writer
{
}
