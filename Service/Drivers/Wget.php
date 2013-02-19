<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

class Wget extends DownloaderAbstract implements DownloaderInterface
{
    /**
     * Copy the file from one location to another
     * @param String $from
     * @param String $to
     */
    public function _doCopy($from, $to)
    {
        exec("wget -c -q \"$from\" -O $to");
    }

    /**
     * Check if file is ok
     * @param String $file
     */
    public function _checkFile($file)
    {
        if (0 == filesize($file)) {
            $this->_thoughVoidFile();
        }
    }
}

