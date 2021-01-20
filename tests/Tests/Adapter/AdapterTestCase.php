<?php

namespace Gocobachi\Compressy\Tests\Adapter;

use Gocobachi\Compressy\Tests\TestCase;

abstract class AdapterTestCase extends TestCase
{
    public function testIsSupported()
    {
        $adapter = $this->provideSupportedAdapter();
        $this->assertTrue($adapter->isSupported());
    }

    public function testIsNotSupported()
    {
        $adapter = $this->provideNotSupportedAdapter();
        $this->assertFalse($adapter->isSupported());
    }

    abstract protected function provideNotSupportedAdapter();

    abstract protected function provideSupportedAdapter();
}
