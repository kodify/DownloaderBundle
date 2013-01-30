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
        $this->copy($from, $to);
    }

    protected function isDirectory($path)
    {
        return !is_dir($path);
    }


    /**
     * Can I 
     * @param $path
     * @return bool
     */
    protected function isWritable($path)
    {
        return is_writable(dirname($path));
    }


    /**
     * Validates the from file
     * @param string $from
     * @throws \InvalidArgumentException
     */
    protected function validateFrom($from)
    {
        if (empty($from)) {
            throw new \InvalidArgumentException('Wrong arguments');
        }
    }

    /**
     * Validates the destination file
     * @param string $to
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \InvalidArgumentException
     */
    protected function validateTo($to)
    {
        if (empty($to)) {
            throw new \InvalidArgumentException('Wrong arguments');
        }

        $dirName = dirname($to);

        if (!is_writable($dirName)) {
            throw new FileException('Path is not writable');
        }

        if (!$this->isDirectory($dirName)) {
            if ($this->isWritable($dirName)) {
                mkdir($dirName, 0700);
            } else {
                throw new FileException('Path can not be created');
            }
        }

    }

    /**
     * Compy the file from one location to another
     * @param String $from
     * @param String $to
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    protected function copy($from, $to)
    {
        $from   = escapeshellcmd($from);
        $to     = escapeshellcmd($to);

        // FIXME I understand this is for large files but this
        // should be changed for something less dangerous
        $cmd = "wget -c -q \"$from\" -O $to";
        exec($cmd);

        if (0 == filesize($to)) {
            throw new FileException('Void file downloaded');
        }
    }



}