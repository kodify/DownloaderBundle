<?php
namespace Kodify\DownloaderBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Downloader
{
    public function downloadFile($url, $path, $destinationFileName, $params = array(), $escapeShellCmd = true)
    {
        if (empty($path) || empty($url) || empty($destinationFileName)) {
            throw new \InvalidArgumentException('Wrong arguments');
        }

        if ($this->pathShouldBeCreated($path)) {
            if ($this->pathCanBeCreated($path)) {
                mkdir($path, 0777, true);
            } else {
                throw new FileException('Path can not be created');
            }
        }

        if (is_writable(dirname($path))) {
            $outputFile = $path . $destinationFileName;

            if ($escapeShellCmd) {
                $url = escapeshellcmd($url);
                $outputFile = escapeshellcmd($outputFile);
            }

            $strParams = '';
            if (is_array($params) && !empty($params)) {
                foreach ($params as $value) {
                    $strParams .= $value . ' ';
                }
            }

            $cmd = "wget $strParams \"$url\" -O $outputFile";
            exec($cmd);
            if (0 == filesize($outputFile)) {
                throw new FileException('Void file downloaded');
            }

        } else {
            throw new FileException('Path is not writable');
        }
    }

    public function pathShouldBeCreated($path)
    {
        return !is_dir($path);
    }

    public function pathCanBeCreated($path)
    {
       if (is_dir(dirname($path)) && is_writable(dirname($path))) {
            return true;
        } else {
            if (!is_dir(dirname($path)) && $path != '/') {
                return $this->pathCanBeCreated(dirname($path));
            }
        }

        return false;
    }
}

