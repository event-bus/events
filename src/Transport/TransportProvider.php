<?php

namespace Aztech\Events\Transport;

interface TransportProvider
{
    function canRead();
    
    function getReader();
    
    function canWrite();
    
    function getWriter();
}