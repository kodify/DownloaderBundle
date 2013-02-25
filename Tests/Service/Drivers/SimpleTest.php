<?php

namespace Kodify\DownloaderBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Kodify\DownloaderBundle\Service\Drivers\Simple;

class SimpleTest extends \PHPUnit_Framework_TestCase
{
    public function testDoCopy()
    {
        $file = tempnam(sys_get_temp_dir(), 'tmp_');
        $file2 = tempnam(sys_get_temp_dir(), 'tmp_');
        file_put_contents($file, 'supu');

        $o = new Simple;
        $o->copy($file, $file2);

        $this->assertEquals('supu', file_get_contents($file2));

        unlink($file);
        unlink($file2);
    }
}
