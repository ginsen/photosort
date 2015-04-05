<?php
namespace PhotoSort\lib;

/**
 * Class PhotoSort
 *
 * @package PhotoSort\lib
 */
class PhotoSort
{
    const FORMAT_TYPE_TIMESTAMP = 1;
    const FORMAT_TYPE_INTEGER = 2;

    protected $formatType = self::FORMAT_TYPE_TIMESTAMP;
    protected $count = 0;
    protected $verbose = false;
    protected $output = array();

    protected $sourcePath = null;
    protected $destinyPath = null;


    /**
     * Constructor class
     * @param bool $verbose
     */
    public function __construct($verbose=false)
    {
        $this->verbose = $verbose;
    }


    /**
     * Set sourcePath
     * @param string $sourcePath
     *
     * @return $this
     */
    public function setSourcePath($sourcePath)
    {
        if(!is_dir($sourcePath)) {
            throw new \RuntimeException("The source path is not valid.\nSource path: ".$sourcePath);
        }

        $this->sourcePath = $this->sanitizePath($sourcePath);

        return $this;
    }


    /**
     * Set destinyPath
     * @param string $destinyPath
     *
     * @return $this
     */
    public function setDestinyPath($destinyPath)
    {
        if (!is_dir($destinyPath)) {
            if (!mkdir($destinyPath, 0755, true)) {
                throw new \RuntimeException("The destiny path is not valid.\nDestiny path: ".$destinyPath);
            } else {
                rmdir($destinyPath);
            }
        }

        $this->destinyPath = $this->sanitizePath($destinyPath);

        return $this;
    }


    /**
     * Set formatType
     * @param string $formatType
     *
     * @return $this
     */
    public function setFormatType($formatType)
    {
        if (!preg_match('/time|number/', $formatType)) {
            throw new \RuntimeException("Invalid format\nTry again with 'time' or 'number'");
        }

        if ($formatType == 'number') {
            $this->formatType = self::FORMAT_TYPE_INTEGER;
        }

        return $this;
    }


    /**
     * Copy all images of one directory to another directory and sort images by created date
     *
     * @param string|null $sourcePath
     * @param string|null $destinyPath
     * @param string|null $formatType
     *
     * @return array
     */
    public function copy($sourcePath=null, $destinyPath=null, $formatType=null)
    {
        if (!empty($sourcePath)) {
            $this->setSourcePath($sourcePath);
        }

        if (!empty($destinyPath)) {
            $this->setDestinyPath($destinyPath);
        }

        if (!empty($formatType)) {
            $this->setFormatType($formatType);
        }

        if ($this->sourcePath === null || $this->destinyPath === null) {
            throw new \RuntimeException("Source path or destiny path is null");
        }

        $destinyPath = $this->obtainDestinyPath();
        $files = $this->getImagesSourcePath();

        if(count($files))
        {
            $this->count = 0;
            foreach($files as $file) {
                $newFile = $this->getNewName($file);

                if (!copy($this->sourcePath.$file, $destinyPath.$newFile)) {
                    throw new \RuntimeException("You don't have permissions to write in destiny directory");
                }
            }
        }

        return $this->output;
    }


    /**
     * Rename all images files of one directory
     *
     * @param string|null $sourcePath
     * @param string|null $formatType
     *
     * @return array
     */
    public function rename($sourcePath=null, $formatType=null)
    {
        if (!empty($sourcePath)) {
            $this->setSourcePath($sourcePath);
        }

        if (!empty($formatType)) {
            $this->setFormatType($formatType);
        }

        if ($this->sourcePath === null) {
            throw new \RuntimeException("Source path is null");
        }

        $files = $this->getImagesSourcePath();

        if(count($files))
        {
            $this->count = 0;
            foreach($files as $file) {
                $newFile = $this->getNewName($file);

                if (!rename($this->sourcePath.$file, $this->sourcePath.$newFile)) {
                    throw new \RuntimeException("You don't have permissions to write in destiny directory");
                }
            }
        }

        return $this->output;
    }


    /**
     * Sanitize string path
     * @param string $path
     *
     * @return string
     */
    private function sanitizePath($path)
    {
        if (substr($path, -1, 1) != "/") {
            $path .= "/";
        }

        return $path;
    }


    /**
     * Get Destiny path, if path is not a directory, it is created
     *
     * @return null
     */
    private function obtainDestinyPath()
    {
        if (!is_dir($this->destinyPath)) {
            if (!mkdir($this->destinyPath, 0755, true)) {
                throw new \RuntimeException('Failed create destiny directory');
            }
        }

        return $this->destinyPath;
    }


    /**
     * Get images file list of source path
     *
     * @return array
     */
    private function getImagesSourcePath()
    {
        if ($this->verbose) {
            $this->printMsg("Finding images files...\n");
        }


        $filesArr = array();
        if ($dh = opendir($this->sourcePath)) {
            while (($file = readdir($dh)) !== false) {
                if($file != '.' && $file != '..') {
                    if (preg_match('~\.(jpg|jpeg|tiff{1})$~i', $file)) {
                        $filesArr[] = $file;
                    }
                }
            }
            closedir($dh);
        }
        asort($filesArr);


        if ($this->verbose) {
            if (count($filesArr)) {
                $this->printMsg('Find '.count($filesArr) . " images files.\n");
            } else {
                $this->printMsg("Oops! Images not found.\n");
            }
        }

        return $filesArr;
    }


    /**
     * Create new name from file properties
     * @param string $file
     *
     * @return string
     */
    private function getNewName($file)
    {
        $name = $this->getNameByFile($file);
        $ext = $this->getExtensionByFile($file);

        $newFile = ($ext) ? $name . '.' . $ext : $name;

        if ($this->verbose) {
            $this->output[] = array($file, $newFile);
        }

        return $newFile;
    }


    /**
     * Get new name of file
     * @param string $file
     *
     * @return string
     */
    private function getNameByFile($file)
    {
        if($this->formatType == self::FORMAT_TYPE_TIMESTAMP) {
            $name = $this->extractNameByExifFile($file);

        } else {
            $this->count++;
            $name = sprintf("%04d", $this->count);
        }

        return $name;
    }


    /**
     * Extract info file by data EXIF file
     * @param string $file
     *
     * @return null|string
     */
    private function extractNameByExifFile($file)
    {
        $exif = exif_read_data($this->sourcePath . $file, 0, true);
        $name = null;

        $hasIFD0DateTime = (!empty($exif['IFD0']) && !empty($exif['IFD0']['DateTime']));
        if ($hasIFD0DateTime) {
            $name = $this->extractDate($exif['IFD0']['DateTime']);
        }

        $hasEXIFDateTimeDigitized = (!empty($exif['EXIF']) && !empty($exif['EXIF']['DateTimeDigitized']));
        if (empty($name) && $hasEXIFDateTimeDigitized) {
            $name = $this->extractDate($exif['EXIF']['DateTimeDigitized']);
        }

        $hasEXIFDateTimeOriginal = (!empty($exif['EXIF']) && !empty($exif['EXIF']['DateTimeOriginal']));
        if (empty($name) && $hasEXIFDateTimeOriginal) {
            $name = $this->extractDate($exif['EXIF']['DateTimeOriginal']);
        }

        $hasFILEFileDateTime = (!empty($exif['FILE']) && !empty($exif['FILE']['FileDateTime']));
        if (empty($name) && $hasFILEFileDateTime) {
            $name = date('Y-m-d H:i:s', $exif['FILE']['FileDateTime']);
        }

        if (empty($name)) {
            throw new \RuntimeException('The name of file is empty');
        }

        return $name;
    }


    /**
     * Parser string date
     * @param string $strDate
     *
     * @return null|string
     */
    private function extractDate($strDate)
    {
        if (preg_match('~^(\d{4}):(\d{2}):(\d{2}) (\d{2}):(\d{2}):(\d{2})$~', $strDate, $match)) {
            if ($match[1] !== '0000' && $match[1] !== '1970') {
                return sprintf('%s-%s-%s %s:%s:%s', $match[1], $match[2], $match[3], $match[4], $match[5], $match[6]);
            }
        }

        return null;
    }


    /**
     * Get Extension of file
     * @param string $file
     *
     * @return string|null
     */
    private function getExtensionByFile($file)
    {
        $tmp = explode('.',$file);
        $ext = (count($tmp) > 1) ? array_pop($tmp) : null;
        $ext = strtolower($ext);

        if ('jpeg' == $ext) {
            $ext = 'jpg';
        }

        return $ext;
    }


    /**
     * Print message in console
     * @param $msg
     */
    private function printMsg($msg)
    {
        fwrite(STDOUT, $msg . "\n");
    }
}