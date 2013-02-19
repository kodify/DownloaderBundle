<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

class Curl extends DownloaderAbstract implements DownloaderInterface
{
    /**
     * Copy the file from one location to another
     * @param String $from
     * @param String $to
     */
    public function _doCopy($from, $to)
    {
        $fp = fopen ($to, 'w+');
        $ch = curl_init(str_replace(" ", "%20", $from));

        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        curl_close($ch);

        fwrite($fp, $data);
        fclose($fp);
    }

}
