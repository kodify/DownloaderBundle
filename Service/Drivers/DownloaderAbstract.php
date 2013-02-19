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
    }

    /**
     * Throw a void file downloaded exception
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    protected function _thoughVoidFile()
    {
        throw new FileException('Void file downloaded');
    }
}