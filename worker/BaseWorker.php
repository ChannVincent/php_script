<?php

class BaseWorker
{
    /*
     * Attributes
     */
    protected $_base_directory;
    protected $_input_directory;
    protected $_output_directory;
    protected $_input_files;

    /**
     * Constructor
     */
    public function __construct($_base_directory, $_input_directory, $_output_directory)
    {
        $this->_base_directory = $_base_directory;
        $this->_input_directory = $_input_directory;
        $this->_output_directory = $_output_directory;
        $this->_input_files = scandir($_input_directory);
    }

    /*
    * Copy and rename a directory or a file named "$src" from input directory into "dst" in output directory
    */
    protected function copyFile($src, $dst) {
        if (!file_exists($this->_input_directory.DS.$src)) {
            return 0;
        }

        // copy directory in output folder
        if (is_dir($this->_input_directory.DS.$src)) {
            if (!FileUtils::copyDirectory($this->_input_directory.DS.$src, $this->_output_directory.DS.$dst)) {
                echo "fail to copy ".$src."\n";
            }
            else {
                echo "copied ".$src."\n";
            }
        }

        // copy file in output folder
        else {
            if (!copy($this->_input_directory.DS.$src, $this->_output_directory.DS.$dst)) {
                echo "fail to copy ".$src."\n";
            }
            else {
                echo "copied ".$src."\n";
            }
        }

        return filesize($this->_input_directory.DS.$src);
    }

    protected function copyDefaultFile($src, $dst) {
        if (!file_exists($src)) {
            return 0;
        }

        // copy directory in output folder
        if (is_dir($src)) {
            if (!FileUtils::copyDirectory($src, $dst)) {
                echo "fail to copy ".$src."\n";
            }
            else {
                echo "copied ".$src."\n";
            }
        }

        // copy file in output folder
        else
        {
            if (!copy($src, $dst)) {
                echo "fail to copy ".$src."\n";
            }
            else {
                echo "copied ".$src."\n";
            }
        }

        return filesize($src);
    }

    /*
     * Zip all directories in output directory
     */
    protected function zipOutputDirectories()
    {
        $directories = scandir($this->_output_directory);

        foreach($directories as $directory) {
            if (!FileUtils::endsWith($directory, ".") && !FileUtils::endsWith($directory, ".." && !FileUtils::endsWith($directory, ".DS_Store"))) {
                ZipUtils::zipFilesInDirectory($this->_output_directory.DS.$directory, $this->_output_directory.DS.$directory, "*");
            }
        }
    }

    /*
     * Delete all directories and files in output directory except zip
     */
    protected function cleanDirectories($clean_directories, $clean_files)
    {
        $directories = scandir($this->_output_directory);

        foreach($directories as $directory) {
            if (($directory != ".") && ($directory != "..")) {
                if (is_dir($this->_output_directory.DS.$directory) && $clean_directories) {
                    if (FileUtils::deleteDirectory($this->_output_directory.DS.$directory)) {
                        echo "delete directory ".$directory."\n";
                    }
                }

                else if (!is_dir($this->_output_directory.DS.$directory) && !FileUtils::endsWith($directory, ".zip") && $clean_files) {
                    if (rmdir($this->_output_directory.DS.$directory)) {
                        echo "delete directory ".$directory."\n";
                    }
                }
            }

        }
    }

    /*
     * Log Methods
     */
    public function __toString() {
        return "_base_directory : ".$this->_base_directory."\n".
            "_input_directory : ".$this->_input_directory."\n".
            "_output_directory : ".$this->_output_directory."\n";
    }

}