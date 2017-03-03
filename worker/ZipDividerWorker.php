<?php



class ZipDividerWorker extends BaseWorker {

    /*
     * Attributes
     */
    protected $_max_directory_size = 48; // Mo

    protected $_audio_directory = "content_audio";
    protected $_structure_directory = "content_structure";
    protected $_app_content_directory = "content_image";
    protected $_movie_directory = "content_movie";
    protected $_image_directory = "content_image";
    protected $_ui_directory = "content_ui";
    protected $_html_directory = "content_html";

    /*
     * constructor
     */
    public function __construct($_base_directory, $_input_directory, $_output_directory)
    {
        parent::__construct($_base_directory, $_input_directory, $_output_directory);
        $this->_max_directory_size *= 1000 * 1000;
    }

    /*
     * Methods
     */
    protected function copyImageFiles()
    {
        mkdir($this->_output_directory.DS.$this->_image_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_image_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_image_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if (FileUtils::contains($filename, "media") && (FileUtils::endsWith($filename, ".png") || FileUtils::endsWith($filename, ".jpg")))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_image_directory.DS.$filename);
                echo "size ".$this->_image_directory." folder : ".($directory_size / (1000 * 1000))."Mo\n";
            }
        }
    }

    protected function copyUIFiles()
    {
        mkdir($this->_output_directory.DS.$this->_ui_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_ui_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_ui_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if ((FileUtils::endsWith($filename, ".png") || FileUtils::endsWith($filename, ".jpg")) && !FileUtils::contains($filename, 'media'))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_ui_directory.DS.$filename);
            }
        }
    }

    protected function copyHTMLFiles()
    {
        mkdir($this->_output_directory.DS.$this->_html_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_html_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_html_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if ((is_dir($this->_input_directory.DS.$filename) && FileUtils::contains($filename, "media")) || FileUtils::endsWith($filename, ".html"))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_html_directory.DS.$filename);
            }
        }
    }

    protected function copyStructure()
    {
        mkdir($this->_output_directory.DS.$this->_structure_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_structure_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_structure_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if (FileUtils::endsWith($filename, "app_structure.json"))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_structure_directory.DS.$filename);
            }
        }
    }

    protected function copyAppContent()
    {
        mkdir($this->_output_directory.DS.$this->_app_content_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_app_content_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_app_content_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if (FileUtils::endsWith($filename, "app_content.json") || FileUtils::endsWith($filename, "app_description.json"))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_app_content_directory.DS.$filename);
            }
        }
    }

    protected function copyAudio()
    {
        mkdir($this->_output_directory.DS.$this->_audio_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_audio_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_audio_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if (FileUtils::endsWith($filename, ".m4a"))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_audio_directory.DS.$filename);
            }
        }
    }

    protected function copyMovie()
    {
        mkdir($this->_output_directory.DS.$this->_movie_directory, 0777, true);
        $directory_size = 0;

        foreach ($this->_input_files as $filename)
        {
            // create alternative folder when max size folder is reach
            if ($directory_size > $this->_max_directory_size)
            {
                $this->_movie_directory .= "_bis";
                mkdir($this->_output_directory.DS.$this->_movie_directory, 0777, true);
                $directory_size = 0;
            }

            // filter
            if (FileUtils::endsWith($filename, ".mp4"))
            {
                // copy
                $directory_size += self::copyFile($filename, $this->_movie_directory.DS.$filename);
            }
        }
    }

    /*
     * Start process
     */
    public function run()
    {
        $this->copyImageFiles();
        $this->copyUIFiles();
        $this->copyHTMLFiles();
        $this->copyStructure();
        $this->copyAppContent();
        $this->copyAudio();
        $this->copyMovie();

        $this->zipOutputDirectories();
        $this->cleanDirectories(true, true);
    }

    /*
     * Log
     */
    public function __toString()
    {
        return parent::__toString().
            "_max_directory_size : ".$this->_max_directory_size."\n".
            "_input_files : ".$this->_input_files."\n";
    }

}