<?php

namespace Kodify\DownloaderBundle\Service;

function exec($command)
{
    $destParam = '-O';
    $destinationFilePath = trim(substr($command, strpos($command, $destParam) + strlen($destParam), strlen($command)));

    file_put_contents($destinationFilePath . '.out', $command);

    return \exec($command);
}


namespace Kodify\DownloaderBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Kodify\DownloaderBundle\Service\Downloader;

/**
 * @group test
 */
class DownloaderExecMockedTest extends \PHPUnit_Framework_TestCase
{
    protected $downloader;
    protected $filename = 'downloadedFileTest2.pl';
    protected $path = '/tmp/testWithParams/';

    public function setUp()
    {

        $this->downloader = new Downloader();
    }

    public function tearDown()
    {
        unlink($this->path . $this->filename);
        unlink($this->path . $this->filename . '.out');
        rmdir($this->path);
    }

    public function paramsDataProvider()
    {
        $params1 = array('-q', '-c');
        $params2 = null;
        $params3 = array();
        $params4 = new \stdClass();

        return array(
            array($params1, 'wget -q -c  "http://www.google.com/robots.txt" -O ' . $this->path . $this->filename),
            array($params2, 'wget  "http://www.google.com/robots.txt" -O ' . $this->path . $this->filename),
            array($params3, 'wget  "http://www.google.com/robots.txt" -O ' . $this->path . $this->filename),
            array($params4, 'wget  "http://www.google.com/robots.txt" -O ' . $this->path . $this->filename)
        );
    }

    /**
     * @dataProvider paramsDataProvider
     */
    public function testCmdWithParams($params, $expected)
    {
        @mkdir($this->path, 0700);

        $downloadUrl = 'http://www.google.com/robots.txt';

        $this->downloader->downloadFile($downloadUrl, $this->path, $this->filename, $params);

        $finalFile = $this->path . $this->filename;
        $this->assertTrue(file_exists($finalFile . '.out'), 'File was not created');
        $this->assertEquals($expected, file_get_contents($finalFile . '.out'));
    }
}
