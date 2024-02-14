<?php

//namespace League\Flysystem\Plugin;

//use League\Flysystem\FilesystemInterface;
//use League\Flysystem\PluginInterface;

require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'League' . DS . 'Flysystem' . DS . 'FilesystemInterface.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'League' . DS . 'Flysystem' . DS . 'PluginInterface.php');

abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
