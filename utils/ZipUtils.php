<?php
class ZipUtils {

    /*
     * zip all files in directory
     * $type :
     * - '*' : zip all files in directory '$dst'
     * - '*.png' : zip all PNG files in directory '$dst'
     * - '*.*' : zip all files in directory '$dst' except directories
     */
    public static function zipFilesInDirectory($src, $dst, $type) {
        $cmd = 'cd '.$src.' && zip -r '.$dst.'.zip '.$type;
        echo $cmd."\n";
        return shell_exec($cmd);
    }


    public static function zipDirectory($src, $dst) {
        $cmd = 'cd '.dirname($src).' && zip -r '.$dst.'.zip '.basename($src);
        echo $cmd."\n";
        return shell_exec($cmd);
    }

    /*
     * unzip at the same location where $src zip is
     */
    public static function unzip($src) {
        $cmd = 'unzip '.$src;
        echo $cmd."\n";
        return shell_exec($cmd);
    }
}