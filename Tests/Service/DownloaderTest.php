<?php

namespace Kodify\DownloaderBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Kodify\DownloaderBundle\Service\Downloader;

class DownloaderTest extends \PHPUnit_Framework_TestCase
{

    protected $downloader;


    public function setUp()
    {
        $this->downloader = new Downloader();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDownloadFileWithoutPath()
    {
        $this->downloader->downloadFile('aaa', '', 'bbbb');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDownloadFileWithoutUrl()
    {
        $this->downloader->downloadFile('', 'aaa', 'bbbb');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDownloadFileWithoutDestination()
    {
        $this->downloader->downloadFile('aaa', 'bbb', '');
    }

    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testDownloadFileWithPathNonWritable()
    {
        $nonWritablePath = '/etc/';
        $this->downloader->downloadFile('aaa', $nonWritablePath, 'bbb');
    }


    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testDownloadFileWithPathNonExistingAndNonWritable()
    {
        $nonWritablePath = '/etc/test/';
        $this->downloader->downloadFile('aaaa', $nonWritablePath, 'bbbb');
    }

    public function testDownloadFileOKCreatingDirectory()
    {
        $path = '/tmp/test/';

        $downloadUrl = 'http://www.google.com/robots.txt';
        $filename = 'downloadedFile.pl';
        $this->downloader->downloadFile($downloadUrl, $path, $filename);
        $this->assertTrue(is_dir($path));
        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertGreaterThan(0, filesize($finalFile), 'Filesize is not correct');

        unlink($finalFile);
        unlink($finalFile . '.out');
        rmdir($path);
    }

    public function testDownloadFileOKNotCreatingDirectory()
    {
        $path = '/tmp/test/';
        mkdir($path, 0700);
        $downloadUrl = 'http://www.google.com/robots.txt';
        $filename = 'downloadedFile.pl';
        $this->downloader->downloadFile($downloadUrl, $path, $filename);
        $this->assertTrue(is_dir($path));
        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertGreaterThan(0, filesize($finalFile), 'Filesize is not correct');

        unlink($finalFile);
        unlink($finalFile . '.out');
        rmdir($path);
    }

    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testDownloadFileWrongUrl()
    {
        $path = '/tmp/test/';
        mkdir($path, 0700);
        $downloadUrl = '\agfdsñakdsf';
        $filename = 'downloadedFile.pl';
        $this->downloader->downloadFile($downloadUrl, $path, $filename);
        $this->assertTrue(is_dir($path));
        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertSame(0, filesize($finalFile), 'Filesize is not correct');

        unlink($finalFile);
        unlink($finalFile . '.out');
        rmdir($path);
    }

    /**
     * Fix for command line injection on exec
     */
    public function testCmdHacking()
    {
        $dir = "/tmp/supu";
        @rmdir($dir);
        $injectedCode = 'mkdir ' . $dir;

        $downloadUrl = '"; ' . $injectedCode . '; "';
        $path = '/tmp/test/';
        $filename = 'downloadedFile.pl';
        try {
            $this->downloader->downloadFile($downloadUrl, $path, $filename);
        } catch(FileException $e) {
            $this->assertFalse(is_dir($dir));
        }

    }
}
