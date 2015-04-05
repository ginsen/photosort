<?php

use PhotoSort\lib\PhotoSort;

/**
 * Class PhotoSortTest
 */
class PhotoSortTest extends \PHPUnit_Framework_TestCase
{
    protected $fooDir;
    protected $varDir;


    /**
     * Set Up
     */
    protected function setUp()
    {
        $this->fooDir = getcwd() . '/tests/testDirectories/foo';
        $this->varDir = getcwd() . '/tests/testDirectories/var';
    }


    /**
     * Tear Down
     */
    protected function tearDown()
    {
        $this->removeContent($this->fooDir);
        $this->removeContent($this->varDir);
    }


    /**
     * @test
     */
    public function testIstanceOfPhotoSort()
    {
        $this->assertInstanceOf('PhotoSort\lib\PhotoSort', new PhotoSort());
    }


    /**
     * @test
     */
    public function testDestinyPath()
    {
        $photoSort = new PhotoSort();

        $path = sys_get_temp_dir() . '/testDestinyPath\ \(test\)';
        $photoSort->setDestinyPath($path);
    }


    /**
     * @test
     */
    public function testPhotoSortCopy()
    {
        $this->copyFixturesIntoDir($this->fooDir);

        $photoSort = new PhotoSort();
        $photoSort->copy($this->fooDir, $this->varDir);

        $this->assertFileExists($this->varDir.'/2015-03-14 12:57:43.jpg');
    }


    /**
     * @test
     */
    public function testPhotoSortRename()
    {
        $this->copyFixturesIntoDir($this->fooDir);

        $photoSort = new PhotoSort();
        $photoSort->rename($this->fooDir);

        $this->assertFileExists($this->fooDir.'/2015-03-14 12:57:43.jpg');
    }


    /**
     * Remove directory recursive
     *
     * @param string $dir
     */
    private function removeContent($dir)
    {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (( $file != '.' ) && ( $file != '..' ) && ($file != '.gitignore')) {
                    unlink($dir .'/'. $file);
                }
            }
            closedir($dh);
        }
    }


    /**
     * Copy fixture files on test dir
     *
     * @param string $dir
     */
    private function copyFixturesIntoDir($dir)
    {
        $fixturesDir = getcwd() . '/tests/fixtures';

        if ($dh = opendir($fixturesDir)) {
            while(false !== ( $file = readdir($dh)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    copy($fixturesDir . '/' . $file, $dir . '/' . $file);
                }
            }
        }
        closedir($dh);
    }
}