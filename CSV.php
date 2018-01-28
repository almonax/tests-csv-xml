<?php
/**
 * Created by PhpStorm.
 * User: Maks
 * Date: 27-Jan-18
 * Time: 20:41
 */

class CSV
{
    const MODE_LINE  = 1;
    const MODE_ARRAY = 0;

    /**
     * @var null|string
     */
    private $csvFile = null;

    /**
     * @var resource
     */
    private $file;

    /**
     * @var int
     */
    private $mode;

    /**
     * CSV constructor.
     * @param string $csvFile
     * @param int $mode
     */
    public function __construct($csvFile, $mode = self::MODE_ARRAY) {
        $this->setMode($mode);
        $this->csvFile = $csvFile;
    }

    /**
     * Close file
     */
    public function __destruct()
    {
        $this->closeFile();
    }

    /**
     * Set mode
     * @param int $mode
     */
    public function setMode($mode)
    {
        $this->mode = ($mode == self::MODE_LINE)
            ? self::MODE_LINE
            : self::MODE_ARRAY;
    }

    /**
     * Set new file. Old file can be close
     * @param string $csvFile
     */
    public function setFile($csvFile)
    {
        $this->closeFile();
        $this->csvFile = $csvFile;
    }

    /**
     * Get data from file
     * @return array
     */
    public function get()
    {
        $this->fileHandler();
        return ($this->mode)
            ? $this->getLine()
            : $this->getAll();
    }

    /**
     * Set data into file
     * @param array $arrayValues
     * @return bool|int
     */
    public function set($arrayValues)
    {
        $this->fileHandler('w');
        return ($this->mode)
            ? $this->setLine($arrayValues)
            : $this->setAll($arrayValues);
    }

    /**
     * Set new line in file
     * @param array $arrayValues
     * @return bool|int
     */
    private function setLine($arrayValues)
    {
        return fputcsv($this->file, $arrayValues, ';');
    }

    /**
     * Get one line
     * @return array
     */
    private function getLine()
    {
        return fgetcsv($this->file, 0, ';');
    }

    /**
     * Get all file data
     * @return array
     */
    private function getAll()
    {
        $arrayLineFull = [];

        while (($line = $this->getLine()) !== false) {
            $arrayLineFull[] = $line;
        }

        $this->closeFile();
        return $arrayLineFull;
    }

    /**
     * Set all array in file
     * @param array $arrayValues
     * @return bool|int
     */
    private function setAll($arrayValues)
    {
        $length = 0;
        foreach ($arrayValues as $value) {
            if ($length += $this->setLine($value) === false) {
                break;
            }
        }

        $this->closeFile();
        return $length;
    }

    /**
     * Open file with mode
     * @param string $fileMode
     */
    private function fileHandler($fileMode = 'r')
    {
        try {
            if (!is_resource($this->file)) {
                $this->file = fopen($this->csvFile, $fileMode);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Close file descriptor
     */
    private function closeFile()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }

}