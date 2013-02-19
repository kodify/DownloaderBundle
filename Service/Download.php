<?php
namespace Kodify\DownloaderBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Download
{
    protected $_driver = null;

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
        return is_writable($path);
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

        if ($this->isDirectory($to)) {
            throw new FileException('File already exists and is a directory');
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
        $this->_driver()->copy($from, $to);
    }


    /**
     * Set the driver to download the file
     * @param Drivers\Downloader $driver
     */
    public function with(Drivers\Downloader $driver)
    {
        $this->_driver = $driver;
    }

    /**
     * Get the driver to do the download
     * @return Drivers\Downloader
     */
    protected function _driver()
    {
        if ($this->_driver === null) {
            $this->_driver = new Drivers\Wget;
        }
        return $this->_driver;
    }

}