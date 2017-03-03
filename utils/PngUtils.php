<?php

class PngUtils {

    /*
     * Resize
     */
    public static function resize($url_src, $url_dst, $resize_ratio)
    {
        $src = imagecreatefrompng($url_src);
        $height = imagesy($src);
        $width = imagesx($src);

        if(!$dst = imagecreatetruecolor(ceil($width * $resize_ratio), ceil($height * $resize_ratio)) )
        {
            echo "Cannot create destination picture.";
            return false;
        }
        imagesavealpha($dst, true);
        $alpha = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $alpha);

        if(!imagecopyresampled($dst, $src, 0, 0, 0, 0, imagesx($dst), imagesy($dst), $width, $height))
        {
            echo "Cannot resized '$src' \n";
            return false;
        }

        if(!imagepng($dst, $url_dst))
        {
            echo "Cannot create png in '$url_dst'";
        }

        return true;
    }

    /*
     * Crop image
     */
    public static function crop($url_src, $url_dst, $from_x_ratio, $from_y_ratio, $ratio_width, $ratio_height)
    {
        $src = imagecreatefrompng($url_src);
        $src_height = imagesy($src);
        $src_width = imagesx($src);
        $src_start_x = $src_height * $from_x_ratio;
        $src_start_y = $src_width * $from_y_ratio;

        $dst_height = ceil($src_height * $ratio_height);
        $dst_width = ceil($src_width * $ratio_width);
        $dst_start_x = 0;
        $dst_start_y = 0;
        $dst = imagecreatetruecolor($dst_width, $dst_height);

        imagecopyresampled($dst, $src,

            $dst_start_x,
            $dst_start_y, // dst y
            $src_start_x, // src x
            $src_start_y, // src y

            $dst_width,
            $dst_height,
            $dst_width,
            $dst_height);

        imagepng($dst, $url_dst);
    }
    
    /*
     * Copy and change brightness
     */
    public static function pngBrightness($src, $dst, $value)
    {
        $image = imagecreatefrompng($src);
        imagesavealpha($image, true);

        if($image && imagefilter($image, IMG_FILTER_CONTRAST, $value))
        {
            echo 'Image brightness changed.'."\n";
            imagepng($image, $dst);
            imagedestroy($image);
        }
        else
        {
            echo 'Image brightness change failed.'."\n";
        }
    }
}