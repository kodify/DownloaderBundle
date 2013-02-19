<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

class Wget extends DownloaderAbstract implements DownloaderInterface
{
    /**
     * Copy the file from one location to another
     * @param String $from
     * @param String $to
     */
    public function copy($from, $to)
    {
        $from   = escapeshellcmd($from);
        $to     = escapeshellcmd($to);

        exec("wget -c -q \"$from\" -O $to");

        if (0 == filesize($to)) {
            $this->_thoughVoidFile();
        }
    }
}

