<?php

class CropResizeImage extends BaseWorker {

    /*
     * Attributes
     */
    public $_final_height = 500;
    public $_final_width = 1024;

    /*
     * constructor
     */
    public function __construct($_base_directory, $_input_directory, $_output_directory)
    {
        parent::__construct($_base_directory, $_input_directory, $_output_directory);
    }

    /*
     * Methods
     */
    public function resizeCropCenterPng($imageName, $final_width, $final_height) {
        echo $imageName."\n";
        if (FileUtils::endsWith($imageName, ".png"))
        {
            $url_src = $this->_input_directory.DS.$imageName;
            $url_dst = $this->_output_directory.DS.$imageName;

            // resize to match width == $final_width
            $src = imagecreatefrompng($url_src);
            $src_width = imagesx($src);
            $ratio_resize = $final_width / $src_width;
            PngUtils::resize($url_src, $url_dst, $ratio_resize);


            // crop to match height == $final_height
            $src = imagecreatefrompng($url_dst);
            $src_height = imagesy($src);
            $ratio_crop = $final_height / $src_height;
            $ratio_start_y = (1 - $ratio_crop) / 2;
            PngUtils::crop($url_dst, $url_dst, 0, $ratio_start_y, 1, $ratio_crop);
        }
    }

    /*
     * Start process
     */
    public function run()
    {
        foreach ($this->_input_files as $input_file) {
            $this->resizeCropCenterPng($input_file, $this->_final_width, $this->_final_height);
        }
    }

    /*
     * Log
     */
    public function __toString()
    {
        return parent::__toString();
    }
}