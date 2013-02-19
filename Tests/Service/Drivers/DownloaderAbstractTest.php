<?php

namespace Kodify\DownloaderBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Kodify\DownloaderBundle\Service\Drivers\Simple;


class DownloaderAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testCopy()
    {
        $stub = $this->getMockForAbstractClass('Kodify\DownloaderBundle\Service\Drivers\Simple');

        $stub->expects($this->once())
            ->method('_checkFile')
            ->with($this->equalTo('to'));

        $stub->expects($this->once())
            ->method('_doCopy')
            ->with($this->equalTo('from'), $this->equalTo('to'))
            ->will($this->returnValue(true));

        $stub->copy('from', 'to');
    }
}
