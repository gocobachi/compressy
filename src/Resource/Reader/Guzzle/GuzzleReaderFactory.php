<?php

namespace Gocobachi\Compressy\Resource\Reader\Guzzle;

use Gocobachi\Compressy\Resource\Resource as ZippyResource;
use Gocobachi\Compressy\Resource\ResourceReader;
use Gocobachi\Compressy\Resource\ResourceReaderFactory;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class GuzzleReaderFactory implements ResourceReaderFactory
{
    /**
     * @var ClientInterface|null
     */
    private $client = null;

    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client;

        if (! $this->client) {
            $this->client = new Client();
        }
    }

    /**
     * @param ZippyResource $resource
     * @param string        $context
     *
     * @return ResourceReader
     */
    public function getReader(ZippyResource $resource, $context)
    {
        return new GuzzleReader($resource, $this->client);
    }
}
