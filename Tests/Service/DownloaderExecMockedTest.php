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


    public function setUp()
    {
        $this->downloader = new Downloader();
    }

    public function paramsDataProvider()
    {
        $params1 = array('-c', '-q');
        $params2 = null;
        $params3 = array();
        $params4 = new \stdClass();

        return array(
            array($params1, 'wget -c -q  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
            array($params2, 'wget  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
            array($params3, 'wget  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
            array($params4, 'wget  "http://www.google.com/robots.txt" -O /tmp/test/downloadedFile.pl'),
        );
    }

    /**
     * @dataProvider paramsDataProvider
     */
    public function testCmdWithParams($params, $expected)
    {
        $path = '/tmp/test/';
        $filename = 'downloadedFile.pl';
        $downloadUrl = 'http://www.google.com/robots.txt';

        $this->downloader->downloadFile($downloadUrl, $path, $filename, $params);

        $finalFile = "{$path}{$filename}";
        $this->assertTrue(file_exists($finalFile . '.out'), 'File was not created');
        $this->assertEquals($expected, file_get_contents($finalFile . '.out'));
    }
}
