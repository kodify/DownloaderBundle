<?php
namespace Kodify\DownloaderBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Downloader
{
    public function downloadFile($url, $path, $destinationFileName)
    {
        if (empty($path) || empty($url) || empty($destinationFileName)) {
            throw new \InvalidArgumentException('Wrong arguments');
        }

        if ($this->pathShouldBeCreated($path)) {
            if ($this->pathCanBeCreated($path)) {
                mkdir($path, 0700);
            } else {
                throw new FileException('Path can not be created');
            }
        }

        $url = escapeshellcmd($url);

        if (is_writable($path)) {
            $outputFile = $path . $destinationFileName;
            $cmd = "wget -c -q \"$url\" -O $outputFile";
            exec($cmd);
            if (0 == filesize($outputFile)) {
                throw new FileException('Void file downloaded');
            }

        } else {
            throw new FileException('Path is not writable');
        }
    }

    protected function pathShouldBeCreated($path)
    {
        return !is_dir($path);
    }


    protected function pathCanBeCreated($path)
    {
        return is_writable(dirname($path));
    }


}