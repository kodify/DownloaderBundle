<?php

namespace Kodify\DownloaderBundle\Service;

function exec($command)
{
    $destParam = '-O';
    $destinationFilePath = trim(substr($command, strpos($command, $destParam) + strlen($destParam), strlen($command)));

    file_put_contents($destinationFilePath, $command);
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


    public function setUp()
    {
        $this->downloader = new Downloader();
    }

    public function timeAgoDataProvider()
    {
        $params1 = array('p1', 'p2', 'p3');
        $params2 = null;
        $params3 = array();
        $params4 = new \stdClass();

        return array(
            array($params1, 'wget p1 p2 p3  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
            array($params2, 'wget  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
            array($params3, 'wget  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
            array($params4, 'wget  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl')
        );
    }

    /**
     * @dataProvider timeAgoDataProvider
     */
    public function testCmdWithParams($params, $expected)
    {
        $path = '/tmp/test/';
        $filename = 'downloadedFile.pl';
        $downloadUrl = 'http://www.google.com/robots.txt';

        $this->downloader->downloadFile($downloadUrl, $path, $filename, $params);

        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile), 'File was not created');
        $this->assertEquals($expected, file_get_contents($finalFile));
    }
}
