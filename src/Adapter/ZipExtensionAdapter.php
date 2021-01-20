<?php

/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gocobachi\Compressy\Adapter;

use Gocobachi\Compressy\Adapter\Resource\ResourceInterface;
use Gocobachi\Compressy\Adapter\Resource\ZipArchiveResource;
use Gocobachi\Compressy\Adapter\VersionProbe\ZipExtensionVersionProbe;
use Gocobachi\Compressy\Archive\Archive;
use Gocobachi\Compressy\Archive\Member;
use Gocobachi\Compressy\Exception\NotSupportedException;
use Gocobachi\Compressy\Exception\RuntimeException;
use Gocobachi\Compressy\Exception\InvalidArgumentException;
use Gocobachi\Compressy\Resource\Resource as ZippyResource;
use Gocobachi\Compressy\Resource\ResourceManager;

/**
 * ZipExtensionAdapter allows you to create and extract files from archives
 * using PHP Zip extension
 *
 * @see http://www.php.net/manual/en/book.zip.php
 */
class ZipExtensionAdapter extends AbstractAdapter
{
    private $errorCodesMapping = [
        \ZipArchive::ER_EXISTS => "File already exists",
        \ZipArchive::ER_INCONS => "Zip archive inconsistent",
        \ZipArchive::ER_INVAL  => "Invalid argument",
        \ZipArchive::ER_MEMORY => "Malloc failure",
        \ZipArchive::ER_NOENT  => "No such file",
        \ZipArchive::ER_NOZIP  => "Not a zip archive",
        \ZipArchive::ER_OPEN   => "Can't open file",
        \ZipArchive::ER_READ   => "Read error",
        \ZipArchive::ER_SEEK   => "Seek error",
    ];

    public function __construct(ResourceManager $manager)
    {
        parent::__construct($manager);
        $this->probe = new ZipExtensionVersionProbe();
    }

    /**
     * @inheritdoc
     */
    protected function doListMembers(ResourceInterface $resource)
    {
        $members = array();
        for ($i = 0; $i < $resource->getResource()->numFiles; $i++) {
            $stat = $resource->getResource()->statIndex($i);
            $members[] = new Member(
                $resource,
                $this,
                $stat['name'],
                $stat['size'],
                new \DateTime('@' . $stat['mtime']),
                0 === strlen($resource->getResource()->getFromIndex($i, 1))
            );
        }

        return $members;
    }

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'zip-extension';
    }

    /**
     * @inheritdoc
     */
    protected function doExtract(ResourceInterface $resource, $to)
    {
        return $this->extractMembers($resource, null, $to);
    }

    /**
     * @inheritdoc
     */
    protected function doExtractMembers(ResourceInterface $resource, $members, $to, $overwrite = false)
    {
        if (null === $to) {
            // if no destination is given, will extract to zip current folder
            $to = dirname(realpath($resource->getResource()->filename));
        }

        if (!is_dir($to)) {
            $resource->getResource()->close();
            throw new InvalidArgumentException(sprintf("%s is not a directory", $to));
        }

        if (!is_writable($to)) {
            $resource->getResource()->close();
            throw new InvalidArgumentException(sprintf("%s is not writable", $to));
        }

        if (null !== $members) {
            $membersTemp = (array) $members;
            if (empty($membersTemp)) {
                $resource->getResource()->close();

                throw new InvalidArgumentException("no members provided");
            }
            $members = array();
            // allows $members to be an array of strings or array of Members
            foreach ($membersTemp as $member) {
                if ($member instanceof Member) {
                    $member = $member->getLocation();
                }

                if ($resource->getResource()->locateName($member) === false) {
                    $resource->getResource()->close();

                    throw new InvalidArgumentException(sprintf('%s is not in the zip file', $member));
                }

                if ($overwrite == false) {
                    if (file_exists($member)) {
                        $resource->getResource()->close();

                        throw new RuntimeException('Target file ' . $member . ' already exists.');
                    }
                }

                $members[] = $member;
            }
        }

        if (!$resource->getResource()->extractTo($to, $members)) {
            $resource->getResource()->close();

            throw new InvalidArgumentException(sprintf('Unable to extract archive : %s', $resource->getResource()->getStatusString()));
        }

        return new \SplFileInfo($to);
    }

    /**
     * @inheritdoc
     */
    protected function doRemove(ResourceInterface $resource, $files)
    {
        $files = (array) $files;

        if (empty($files)) {
            throw new InvalidArgumentException("no files provided");
        }

        // either remove all files or none in case of error
        foreach ($files as $file) {
            if ($resource->getResource()->locateName($file) === false) {
                $resource->getResource()->unchangeAll();
                $resource->getResource()->close();

                throw new InvalidArgumentException(sprintf('%s is not in the zip file', $file));
            }
            if (!$resource->getResource()->deleteName($file)) {
                $resource->getResource()->unchangeAll();
                $resource->getResource()->close();

                throw new RuntimeException(sprintf('unable to remove %s', $file));
            }
        }
        $this->flush($resource->getResource());

        return $files;
    }

    /**
     * @inheritdoc
     */
    protected function doAdd(ResourceInterface $resource, $files, $recursive)
    {
        $files = (array) $files;
        if (empty($files)) {
            $resource->getResource()->close();
            throw new InvalidArgumentException("no files provided");
        }
        $this->addEntries($resource, $files, $recursive);

        return $files;
    }

    /**
     * @inheritdoc
     */
    protected function doCreate($path, $files, $recursive)
    {
        $files = (array) $files;

        if (empty($files)) {
            throw new NotSupportedException("Cannot create an empty zip");
        }

        $resource = $this->getResource($path, \ZipArchive::CREATE);
        $this->addEntries($resource, $files, $recursive);

        return new Archive($resource, $this, $this->manager);
    }

    /**
     * Returns a new instance of the invoked adapter
     *
     * @return AbstractAdapter
     *
     * @throws RuntimeException In case object could not be instanciated
     */
    public static function newInstance()
    {
        return new ZipExtensionAdapter(ResourceManager::create());
    }

    protected function createResource($path)
    {
        return $this->getResource($path, \ZipArchive::CHECKCONS);
    }

    private function getResource($path, $mode)
    {

        $res = \ZipArchive::ER_OPEN;
        $zip = new \ZipArchive();

        try {
            $res = $zip->open($path, $mode);
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } finally {
            if ($res !== true) {
                if (!isset($this->errorCodesMapping[$res])) {
                    throw new RuntimeException('Unknown error when opening the zip file');
                }

                throw new RuntimeException($this->errorCodesMapping[$res]);
            }
        }

        return new ZipArchiveResource($zip);
    }

    private function addEntries(ResourceInterface $zipResource, array $files, $recursive)
    {
        $stack = new \SplStack();

        $error = null;
        $cwd = getcwd();
        $collection = $this->manager->handle($cwd, $files);

        $this->chdir($collection->getContext());

        $adapter = $this;

        try {
            $collection->forAll(function($i, ZippyResource $resource) use ($zipResource, $stack, $recursive, $adapter) {
                $adapter->checkReadability($zipResource->getResource(), $resource->getTarget());
                if (is_dir($resource->getTarget())) {
                    if ($recursive) {
                        $stack->push($resource->getTarget() . ((substr($resource->getTarget(), -1) === DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR));
                    } else {
                        $adapter->addEmptyDir($zipResource->getResource(), $resource->getTarget());
                    }
                } else {
                    $adapter->addFileToZip($zipResource->getResource(), $resource->getTarget());
                }

                return true;
            });

            // recursively add dirs
            while (!$stack->isEmpty()) {
                $dir = $stack->pop();
                // removes . and ..
                $files = array_diff(scandir($dir), array(".", ".."));
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $file = $dir . $file;
                        $this->checkReadability($zipResource->getResource(), $file);
                        if (is_dir($file)) {
                            $stack->push($file . DIRECTORY_SEPARATOR);
                        } else {
                            $this->addFileToZip($zipResource->getResource(), $file);
                        }
                    }
                } else {
                    $this->addEmptyDir($zipResource->getResource(), $dir);
                }
            }
            $this->flush($zipResource->getResource());

            $this->manager->cleanup($collection);
        } catch (\Exception $e) {
            $error = $e;
        }

        $this->chdir($cwd);

        if ($error) {
            throw $error;
        }
    }

    /**
     *
     * @param \ZipArchive $zip
     * @param string      $file
     */
    private function checkReadability(\ZipArchive $zip, $file)
    {
        if (!is_readable($file)) {
            $zip->unchangeAll();
            $zip->close();

            throw new InvalidArgumentException(sprintf('could not read %s', $file));
        }
    }

    /**
     *
     * @param \ZipArchive $zip
     * @param string      $file
     */
    private function addFileToZip(\ZipArchive $zip, $file)
    {
        if (!$zip->addFile($file)) {
            $zip->unchangeAll();
            $zip->close();

            throw new RuntimeException(sprintf('unable to add %s to the zip file', $file));
        }
    }

    /**
     *
     * @param \ZipArchive $zip
     * @param string      $dir
     */
    private function addEmptyDir(\ZipArchive $zip, $dir)
    {
        if (!$zip->addEmptyDir($dir)) {
            $zip->unchangeAll();
            $zip->close();

            throw new RuntimeException(sprintf('unable to add %s to the zip file', $dir));
        }
    }

    /**
     * Flushes changes to the archive
     *
     * @param \ZipArchive $zip
     */
    private function flush(\ZipArchive $zip) // flush changes by reopening the file
    {
        $path = $zip->filename;
        $zip->close();
        $zip->open($path, \ZipArchive::CHECKCONS);
    }
}
