Downloader Bundle
================

Simple Downloader for the Symfony Framework

What is this Downloader?
------------------------
It's a simple yet functional downloader for symfony that simply makes what it says in the tin, downloads the url
specified in the specified path.

Installation
------------
### Composer:

Add the following dependencies to your projects composer.json file:
      
      "require": {
          "kodify/downloader": "dev-master"
      }
      

Drivers
-------
The system architecture allows you to use different drivers as wget, curl or simple. Feel free to add your own drivers on Service/Drivers.

Usage
-----

- Simple usage:
```php
	$download = new Download();
	$download->file($from, $to);
```

* Default downloads are done with wget


- Using different drivers:
```php
	$driver = new Kodify\DownloaderBundle\Service\Drivers\Simple;
	$download = new Download();
	$download->with($driver)->file($from, $to);
```
     
System dependecies
------------
### Wget:

At the moment this Bundle is using wget software so you need to get installed on your machine.
*This dependency shouldn't be necessary on the future

      
      
    
