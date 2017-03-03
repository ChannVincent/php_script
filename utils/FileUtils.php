<?php
class FileUtils {

    /*
     * DELETE
     */
    public static function deleteDirectory($directory)
    {
        self::deleteFilesInDirectory($directory);
        return rmdir($directory);
    }

    public static function deleteFilesInDirectory($directory)
    {
        if (!file_exists($directory))
        {
            echo $directory." directory doesn't exist !"."\n";
            return NULL;
        }

        $directory_files = scandir($directory);

        foreach ($directory_files as $file)
        {
            if (($file != ".") && ($file != ".."))
            {
                if (is_dir($directory.DS.$file))
                {
                    self::deleteFilesInDirectory($directory.DS.$file);
                    rmdir($directory.DS.$file);
                }
                else
                {
                    unlink($directory.DS.$file);
                }
            }
        }
    }

    /*
     * COPY
     */
    public static function copyDirectory($src, $dst)
    {
        $dir = opendir($src);
        $result = ($dir === false ? false : true);

        if ($result !== false) {
            $result = @mkdir($dst);

            if ($result === true) {
                while(false !== ($file = readdir($dir))) {
                    if (($file != '.') && ($file != '..') && $result) {
                        if (is_dir($src.'/'.$file)) {
                            $result = self::copyDirectory($src.'/'.$file, $dst.'/'.$file);
                        }
                        else {
                            $result = copy($src.'/'.$file, $dst.'/'.$file);
                        }
                    }
                }
                closedir($dir);
            }
        }
        return $result;
    }

    /*
     * NAMING
     */
    public static function endsWith($haystack, $needle)
    {
        $temp = strlen($haystack) - strlen($needle);
        return ($needle === "") || (($temp >= 0) && (strpos($haystack, $needle, $temp) !== FALSE));
    }

    public static function contains($haystack, $needle)
    {
        if (strpos($haystack, $needle) !== false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}