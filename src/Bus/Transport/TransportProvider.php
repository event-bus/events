<?php

namespace Aztech\Events\Bus\Transport;

interface TransportProvider
{

    function canRead();

    function getReader();

    function canWrite();

    function getWriter();
}
