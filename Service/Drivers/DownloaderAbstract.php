<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

abstract class DownloaderAbstract implements DownloaderInterface
{
    public function copy($from, $to)
    {
    }

    protected function _thoughVoidFile()
    {
        throw new FileException('Void file downloaded');
    }
}