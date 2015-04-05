PhotoSort
=========

[![Build Status](https://travis-ci.org/ginsen/photosort.svg?branch=master)](https://travis-ci.org/ginsen/photosort)


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

You can use PhotoSort in one of two ways:

### As a Phar (Recommended)

You may download a ready-to-use version of PhotoSort as a Phar:

```bash
$ sudo curl -LsS https://github.com/ginsen/photosort/blob/master/bin/photosort.phar?raw=true -o /usr/local/bin/photosort
$ sudo chmod a+x /usr/local/bin/photosort
```

The first command will check your PHP settings, warn you of any issues, and the download it to the `/usr/local/bin` directory.
The second command change permissions to execute `photosort` command.

Now you may want check version app.

```bash
$ photosort --version
```