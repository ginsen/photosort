<?php

use PhotoSort\lib\PhotoSort;

/**
 * Class PhotoSortTest
 */
class PhotoSortTest extends \PHPUnit_Framework_TestCase
{
    protected $testDir = '/tmp/foo';


    /**
     * Tear Down
     */
    protected function tearDown()
    {
        if (is_dir($this->testDir)) {
            $this->removeDir($this->testDir);
        }
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
    public function testPhotoSortCopy()
    {
        $fixtureDir = getcwd() . '/tests/fixtures';
        $this->createTestDir($fixtureDir, $this->testDir);
        $varDir = '/tmp/var';

        $photoSort = new PhotoSort();
        $photoSort->copy($this->testDir, $varDir);

        $this->assertFileExists($varDir.'/2015-03-14 12:57:43.jpg');
        $this->removeDir($varDir);
    }


    /**
     * @test
     */
    public function testPhotoSortRename()
    {
        $fixtureDir = getcwd() . '/tests/fixtures';
        $this->createTestDir($fixtureDir, $this->testDir);

        $photoSort = new PhotoSort();
        $photoSort->rename($this->testDir);

        $this->assertFileExists($this->testDir.'/2015-03-14 12:57:43.jpg');
    }


    /**
     * Copy fixture directory structure on another path
     *
     * @param string $fixtureDir
     * @param string $testDir
     */
    protected function createTestDir($fixtureDir, $testDir)
    {
        if (is_dir($testDir)) {
            $this->removeDir($testDir);
        }

        mkdir($testDir, 0755);
        $this->copyRecursiveDir($fixtureDir, $testDir);
    }


    /**
     * Remove directory recursive
     *
     * @param string $dir
     */
    private function removeDir($dir)
    {
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
                RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }


    /**
     * Copy directory recursive
     *
     * @param string $src
     * @param string $dst
     */
    private function copyRecursiveDir($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyRecursiveDir($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}