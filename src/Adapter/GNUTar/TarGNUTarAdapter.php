<?php

namespace Gocobachi\Compressy\Adapter\GNUTar;

use Gocobachi\Compressy\Adapter\AbstractTarAdapter;
use Gocobachi\Compressy\Adapter\VersionProbe\GNUTarVersionProbe;
use Gocobachi\Compressy\Parser\ParserInterface;
use Gocobachi\Compressy\Resource\ResourceManager;
use Gocobachi\Compressy\ProcessBuilder\ProcessBuilderFactoryInterface;

/**
 * GNUTarAdapter allows you to create and extract files from archives using GNU tar
 *
 * @see http://www.gnu.org/software/tar/manual/tar.html
 */
class TarGNUTarAdapter extends AbstractTarAdapter
{
    public function __construct(ParserInterface $parser, ResourceManager $manager, ProcessBuilderFactoryInterface $inflator, ProcessBuilderFactoryInterface $deflator)
    {
        parent::__construct($parser, $manager, $inflator, $deflator);
        $this->probe = new GNUTarVersionProbe($inflator, $deflator);
    }

    /**
     * @inheritdoc
     */
    protected function getLocalOptions()
    {
        return array();
    }

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'gnu-tar';
    }

    /**
     * @inheritdoc
     */
    public static function getDefaultDeflatorBinaryName()
    {
        return array('gnutar', 'tar');
    }

    /**
     * @inheritdoc
     */
    public static function getDefaultInflatorBinaryName()
    {
        return array('gnutar', 'tar');
    }

    /**
     * {@inheritdoc}
     */
    protected function getListMembersOptions()
    {
        return array('--utc');
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtractOptions()
    {
        return array('--overwrite');
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtractMembersOptions()
    {
        return array('--overwrite');
    }
}
