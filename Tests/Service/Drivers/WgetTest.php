<?php

namespace Kodify\DownloaderBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Kodify\DownloaderBundle\Service\Drivers\Wget;

class WgetTest extends \PHPUnit_Framework_TestCase
{
    public function testDoCopy()
    {
        $from = 'http://www.google.es';
        $to = tempnam(sys_get_temp_dir(), 'tmp_');

        $o = new Wget;
        $o->copy($from, $to);

        $this->assertFalse(file_get_contents($to) == '');

        unlink($to);
    }
}
