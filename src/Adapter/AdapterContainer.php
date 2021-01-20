<?php
/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 * (c) Miguel Gocobachi <mgocobachi@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gocobachi\Compressy\Adapter;

use Gocobachi\Compressy\Adapter\BSDTar\TarBSDTarAdapter;
use Gocobachi\Compressy\Adapter\BSDTar\TarBz2BSDTarAdapter;
use Gocobachi\Compressy\Adapter\BSDTar\TarGzBSDTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarBz2GNUTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarGNUTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarGzGNUTarAdapter;
use Gocobachi\Compressy\Resource\RequestMapper;
use Gocobachi\Compressy\Resource\ResourceManager;
use Gocobachi\Compressy\Resource\ResourceTeleporter;
use Gocobachi\Compressy\Resource\TargetLocator;
use Gocobachi\Compressy\Resource\TeleporterContainer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\ExecutableFinder;

class AdapterContainer implements \ArrayAccess
{
    private $items = [];

    /**
     * Builds the adapter container
     *
     * @return AdapterContainer
     */
    public static function load()
    {
        $container = new static();

        $container['zip.inflator'] = null;
        $container['zip.deflator'] = null;

        $container['resource-manager'] = function($container) {
            return new ResourceManager(
                $container['request-mapper'],
                $container['resource-teleporter'],
                $container['filesystem']
            );
        };

        $container['executable-finder'] = function() {
            return new ExecutableFinder();
        };

        $container['request-mapper'] = function($container) {
            return new RequestMapper($container['target-locator']);
        };

        $container['target-locator'] = function() {
            return new TargetLocator();
        };

        $container['teleporter-container'] = function() {
            return TeleporterContainer::load();
        };

        $container['resource-teleporter'] = function($container) {
            return new ResourceTeleporter($container['teleporter-container']);
        };

        $container['filesystem'] = function() {
            return new Filesystem();
        };

        $container[ZipAdapter::class] = function($container) {
            return ZipAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['zip.inflator'],
                $container['zip.deflator']
            );
        };

        $container['gnu-tar.inflator'] = null;
        $container['gnu-tar.deflator'] = null;

        $container[TarGNUTarAdapter::class] = function($container) {
            return TarGNUTarAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['gnu-tar.inflator'],
                $container['gnu-tar.deflator']
            );
        };

        $container[TarGzGNUTarAdapter::class] = function($container) {
            return TarGzGNUTarAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['gnu-tar.inflator'],
                $container['gnu-tar.deflator']
            );
        };

        $container[TarBz2GNUTarAdapter::class] = function($container) {
            return TarBz2GNUTarAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['gnu-tar.inflator'],
                $container['gnu-tar.deflator']
            );
        };

        $container['bsd-tar.inflator'] = null;
        $container['bsd-tar.deflator'] = null;

        $container[TarBSDTarAdapter::class] = function($container) {
            return TarBSDTarAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['bsd-tar.inflator'],
                $container['bsd-tar.deflator']
            );
        };

        $container[TarGzBSDTarAdapter::class] = function($container) {
            return TarGzBSDTarAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['bsd-tar.inflator'],
                $container['bsd-tar.deflator']
            );
        };

        $container[TarBz2BSDTarAdapter::class] = function($container) {
            return TarBz2BSDTarAdapter::newInstance(
                $container['executable-finder'],
                $container['resource-manager'],
                $container['bsd-tar.inflator'],
                $container['bsd-tar.deflator']);
        };

        $container[ZipExtensionAdapter::class] = function() {
            return ZipExtensionAdapter::newInstance();
        };

        return $container;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return bool true on success or false on failure.
     * <p>The return value will be casted to boolean if non-boolean was returned.</p>
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->items) && is_callable($this->items[$offset])) {
            $this->items[$offset] = call_user_func($this->items[$offset], $this);
        }

        if (array_key_exists($offset, $this->items)) {
            return $this->items[$offset];
        }

        throw new \InvalidArgumentException();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
