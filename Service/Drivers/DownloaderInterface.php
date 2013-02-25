<?php

namespace Kodify\DownloaderBundle\Service\Drivers;

interface DownloaderInterface
{
    public function copy($from, $to);
}