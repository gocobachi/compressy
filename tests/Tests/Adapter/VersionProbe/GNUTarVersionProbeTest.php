<?php

namespace Gocobachi\Compressy\Tests\Adapter\VersionProbe;

class GNUTarVersionProbeTest extends AbstractTarVersionProbeTest
{
    public function getProbeClassName()
    {
        return 'Gocobachi\Compressy\Adapter\VersionProbe\GNUTarVersionProbe';
    }

    public function getCorrespondingVersionOutput()
    {
        return $this->getGNUTarVersionOutput();
    }

    public function getNonCorrespondingVersionOutput()
    {
        return $this->getBSDTarVersionOutput();
    }
}
