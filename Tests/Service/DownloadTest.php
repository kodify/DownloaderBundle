<?php

namespace Kodify\DownloaderBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Kodify\DownloaderBundle\Service\Download;

class DownloadTest extends \PHPUnit_Framework_TestCase
{

    protected $downloader;


    public function setUp()
    {
        @rmdir(sys_get_temp_dir() . "test");
        $this->downloader = new Download();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testfileWithoutPath()
    {
        $this->downloader->file('aaa', '');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testfileWithoutUrl()
    {
        $this->downloader->file('', 'aaa');
    }

    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testfileWithPathNonWritable()
    {
        $nonWritablePath = '/etc/';
        $this->downloader->file('aaa', $nonWritablePath);
    }


    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileException
     * @group supu
     */
    public function testfileWithPathNonExistingAndNonWritable()
    {
        $nonWritablePath = '/etc/test/';
        $this->downloader->file('aaaa', $nonWritablePath);
    }

    public function testfileOKCreatingDirectory()
    {
        $path = sys_get_temp_dir() . 'test/';

        $downloadUrl = 'http://www.google.com/robots.txt';
        $filename = 'downloadedFile.pl';
        $this->downloader->file($downloadUrl, $path . $filename);
        $this->assertTrue(is_dir($path));
        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertGreaterThan(0, filesize($finalFile), 'Filesize is not correct');

        unlink($finalFile);
        rmdir($path);
    }

    public function testfileOKNotCreatingDirectory()
    {
        $path = sys_get_temp_dir() . 'test/';
        mkdir($path, 0700);
        $downloadUrl = 'http://www.google.com/robots.txt';
        $filename = 'downloadedFile.pl';
        $this->downloader->file($downloadUrl, $path . $filename);
        $this->assertTrue(is_dir($path));
        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertGreaterThan(0, filesize($finalFile), 'Filesize is not correct');

        unlink($finalFile);
        rmdir($path);
    }

    /**
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testfileWrongUrl()
    {
        $path = sys_get_temp_dir() . 'test/';
        mkdir($path, 0700);
        $downloadUrl = '\agfdsñakdsf';
        $filename = 'downloadedFile.pl';
        $this->downloader->file($downloadUrl, $path . $filename);
        $this->assertTrue(is_dir($path));
        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertSame(0, filesize($finalFile), 'Filesize is not correct');

        unlink($finalFile);
        rmdir($path);
    }

    /**
     * Fix for command line injection on exec
     */
    public function testCmdHacking()
    {
        $dir = sys_get_temp_dir() . "supu";
        @rmdir($dir);
        $injectedCode = 'mkdir ' . $dir;

        $downloadUrl = '"; ' . $injectedCode . '; "';
        $path = sys_get_temp_dir() . 'test/';
        $filename = 'downloadedFile.pl';
        try {
            $this->downloader->file($downloadUrl, $path . $filename);
        } catch(FileException $e) {
            $this->assertFalse(is_dir($dir));
        }
    }

}
