PhotoSort
=========

[![Build Status](https://travis-ci.org/ginsen/photosort.svg?branch=master)](https://travis-ci.org/ginsen/photosort)
[![Latest Stable Version](https://poser.pugx.org/ginsen/photosort/v/stable.svg)](https://packagist.org/packages/ginsen/photosort)
[![Latest Unstable Version](https://poser.pugx.org/ginsen/photosort/v/unstable.svg)](https://packagist.org/packages/ginsen/photosort)
[![License](https://poser.pugx.org/ginsen/photosort/license.svg)](https://packagist.org/packages/ginsen/photosort)


What is it?
-----------
The PhotoSort application is a small tool to rename or copy images files by Exif information of image.

[Exif](http://en.wikipedia.org/wiki/Exchangeable_image_file_format) is a standard that specifies the formats for images,
sound, and ancillary tags used by digital cameras (including smartphones), scanners and other systems handling image and
sound files recorded by digital cameras.

The application can do two things:

- Rename all image files of one directory.
- Copy all image files of one directory to other new directory, if directory destiny not exist, this is created.

Also it has two possibles format name file, by date of Exif information (by default) or by numeric format.


How do I get started?
---------------------

You can install PhotoSort in one of tree ways:

### As a Phar (Recommended)

You may download a ready-to-use version of PhotoSort as a Phar:

```bash
$ sudo curl -LsS https://github.com/ginsen/photosort/blob/master/bin/photosort.phar?raw=true -o /usr/local/bin/photosort
$ sudo chmod a+x /usr/local/bin/photosort
```

The first command will check your PHP settings, warn you of any issues, and the download it to the `/usr/local/bin` directory.
The second command change permissions to execute `photosort` command.

### As a Composer Install

Alternatively, you may use [Composer](https://getcomposer.org/) to download and install PhotoSort as well as its dependencies.

```bash
$ mkdir path/to/photosort
$ cd photosort/
$ composer require ginsen/photosort
```

Once the application is downloaded, you have the command in the following path.

```bash
$ ./vendor/bin/photosort.phar
```

May you wish copy this command into the root path or other path? Then run the command.

```bash
$ cp vendor/bin/photosort.phar photosort
```


### As a Git project

Use this official Git repository.

```bash
$ git clone https://github.com/ginsen/photosort.git
$ cd photosort
$ composer install
```

### Check version app

Now you may want check version app, the last version is ``1.1.1``

if you downloaded the phar file (Recommended method) to use as system command, now you can check the version application:
 
```bash
$ photosort --version
```

otherwise, if you installed with composer or git method, you have the command into your path used:

```bash
$ ./photosort --version
```



