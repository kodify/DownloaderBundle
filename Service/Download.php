<?php
namespace Kodify\DownloaderBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Download
{
    /**
     * Copy a file (url) to a given location
     * @param string $from
     * @param string $to
     */
    public function file($from, $to)
    {
        $this->validateFrom($from);
        $this->validateTo($to);
        $this->get($from, $to);
    }

    protected function pathShouldBeCreated($path)
    {
        return !is_dir($path);
    }


    protected function pathCanBeCreated($path)
    {
        return is_writable(dirname($path));
    }

    protected function validateFrom($from)
    {
        if (empty($from)) {
            throw new \InvalidArgumentException('Wrong arguments');
        }
    }

    protected function validateTo($to)
    {
        if (empty($to)) {
            throw new \InvalidArgumentException('Wrong arguments');
        }

        $dirName = dirname($to);

        if ($this->pathShouldBeCreated($dirName)) {
            if ($this->pathCanBeCreated($dirName)) {
                mkdir($dirName, 0700);
            } else {
                throw new FileException('Path can not be created');
            }
        }

        if (!is_writable($dirName)) {
            throw new FileException('Path is not writable');
        }

    }

    protected function get($from, $to)
    {
        $from   = escapeshellcmd($from);
        $to     = escapeshellcmd($to);

        // TODO I understand this is for large files but this
        // should be changed for something less dangerous
        $cmd = "wget -c -q \"$from\" -O $to";
        exec($cmd);

        if (0 == filesize($to)) {
            throw new FileException('Void file downloaded');
        }
    }



}