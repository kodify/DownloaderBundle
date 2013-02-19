<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

class Simple extends DownloaderAbstract implements DownloaderInterface
{
    /**
     * Copy the file from one location to another
     * @param String $from
     * @param String $to
     */
    public function _doCopy($from, $to)
    {
        file_put_contents($to, file_get_contents($from));
    }
}

