<?php

namespace Gocobachi\Compressy\Resource;

interface ResourceWriter 
{
    public function writeFromReader(ResourceReader $reader, $target);
}
