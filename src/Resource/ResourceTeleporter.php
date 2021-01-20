<?php
/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gocobachi\Compressy\Resource;

use Gocobachi\Compressy\Resource\Resource as ZippyResource;

class ResourceTeleporter
{
    private $container;

    /**
     * Constructor
     *
     * @param TeleporterContainer $container
     */
    public function __construct(TeleporterContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Teleports a resource to its target in the context
     *
     * @param string        $context
     * @param ZippyResource $resource
     *
     * @return ResourceTeleporter
     */
    public function teleport($context, ZippyResource $resource)
    {
        $this
            ->container
            ->fromResource($resource)
            ->teleport($resource, $context);

        return $this;
    }

    /**
     * Creates the ResourceTeleporter with the default TeleporterContainer
     *
     * @return ResourceTeleporter
     */
    public static function create()
    {
        return new static(TeleporterContainer::load());
    }
}
