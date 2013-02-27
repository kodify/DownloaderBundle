<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

abstract class DownloaderAbstract implements DownloaderInterface
{
    /**
     * Copy the file from one location to another
     * @param String $from
     * @param String $to
     */
    public function copy($from, $to)
    {
        $from   = $this->_cleanForCommandLine($from);
        $to     = $this->_cleanForCommandLine($to);

        $this->_doCopy($from, $to);
        $this->_checkFile($to);
    }

    /**
     * Throw a void file downloaded exception
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    protected function _throwVoidFile()
    {
        throw new FileException('Void file downloaded');
    }

    /**
     * Clean value for executing it on command line
     * @param String $value
     * @return String
     */
    protected function _cleanForCommandLine($value)
    {
        return escapeshellcmd($value);
    }

    /**
     * Execute the copy command
     * @param String $from
     * @param String $to
     */
    protected function _doCopy($from, $to)
    {
    }

    /**
     * Check if file is ok
     * @param String $file
     */
    protected function _checkFile($file)
    {
    }


}