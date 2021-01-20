<?php

namespace Gocobachi\Compressy\Tests\Adapter\VersionProbe;

class BSDTarVersionProbeTest extends AbstractTarVersionProbeTest
{
    public function getProbeClassName()
    {
        return 'Gocobachi\Compressy\Adapter\VersionProbe\BSDTarVersionProbe';
    }

    public function getCorrespondingVersionOutput()
    {
        return $this->getBSDTarVersionOutput();
    }

    public function getNonCorrespondingVersionOutput()
    {
        return $this->getGNUTarVersionOutput();
    }
}
