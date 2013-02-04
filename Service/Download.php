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

    /**
     * Download remote files
     * @deprecated
     * @param string $url
     * @param string $path
     * @param string $destinationFileName
     */
    public function downloadFile($url, $path, $destinationFileName)
    {
        return $this->file($url, $path.$destinationFileName);
    }

    /**
     * Check if the given argument is a directory
     * @param string $path
     * @return bool
     */
    protected function isDirectory($path)
    {
        return is_dir($path);
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

        if (!$this->isWritable($dirName)) {
            throw new FileException('Path is not writable');
        }

        if (!$this->isDirectory($dirName)) {
            mkdir($dirName, 0700);
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
        exec("wget -c -q \"$from\" -O $to");

        if (0 == filesize($to)) {
            throw new FileException('Void file downloaded');
        }
    }



}