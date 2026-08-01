<?php

namespace bakery\core;

use function_exists;

class Image
{
    protected const string MSG_FILE_NOT_FOUND = "EXCEPTION: '%s' source-file not found: '%s'!";
    protected const string MSG_IMAGETYPE_NOT_SUPPORTED = "EXCEPTION: '%s' image-type '%s' not supported!";
    protected const string MSG_IMAGE_DEST_TYPE_NOT_SUPPORTED = "EXCEPTION: '%s' image-destination type '%s' not supported! ";

    protected bool $bSourceJPG = false;
    protected bool $bWriteJPG = false;

    protected bool $bSourcePNG = false;
    protected bool $bWritePNG = false;

    protected bool $bSourceGIF = false;
    protected bool $bWriteGIF = false;

    protected bool $bSourceWEBP = false;
    protected bool $bWriteWEBP = false;

    protected bool $bSourceWBMP = false;
    protected bool $bWriteWBMP = false;

    public static $instance;

    /**
     *  Return the instance of this class.
     *
     */
    public static function getInstance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static( func_get_args() );
        }
        return static::$instance;
    }

    protected function __construct() {
        $this->bSourceJPG = function_exists("imagecreatefromjpeg");
        $this->bWriteJPG = function_exists("imagejpeg");
    
        $this->bSourcePNG = function_exists("imagecreatefrompng");
        $this->bWritePNG = function_exists("imagepng");

        $this->bSourceGIF = function_exists("imagecreatefromgif");
        $this->bWriteGIF = function_exists("imagegif");

        $this->bSourceWEBP = function_exists("imagecreatefromwebp");
        $this->bWriteWEBP = function_exists("imagewebp");

        $this->bSourceWBMP = function_exists("imagecreatefromwbmp");
        $this->bWriteWBMP = function_exists("imagewbmp");
    }
    /**
     * Generate a thumbnail from a given image file.
     *
     * @param   string $source      Full source-path.
     * @param   string $destination Full destination-path.
     * @param   int    $size        The width of the target image.
     *
     * @notice  Supported types at this time are 
     *          'jpeg', 'jpg', 'png', 'gif', 'wbmp 'and 'webp'.
     *
     * @return  bool
     */
    public function make_thumb(string $source, string $destination, int $size, int $quality = 90): bool
    {
        // Check if GD is installed
        if (extension_loaded('gd'))
        {
            if (!file_exists($source))
            {
                trigger_error(sprintf(
                    self::MSG_FILE_NOT_FOUND,
                    __METHOD__,
                    $source
                ));
                return false;
            }

            $imageType = strtolower(pathinfo($source, PATHINFO_EXTENSION) ?? "none");

            // First figure out the size of the thumbnail
            list($original_x, $original_y) = getimagesize($source);
            if ($original_x > $original_y)
            {
                $thumb_w = $size;
                $thumb_h = intval($original_y * ($size / $original_x));
            }
            if ($original_x < $original_y)
            {
                $thumb_w = intval($original_x * ($size / $original_y));
                $thumb_h = $size;
            }
            if ($original_x == $original_y)
            {
                $thumb_w = $size;
                $thumb_h = $size;
            }
            
            switch ($imageType)
            {
                case 'jpg':
                case 'jpeg':
                    $source_img = $this->bSourceJPG ? imagecreatefromjpeg($source) : null;
                    break;

                case 'png':
                    $source_img = $this->bSourcePNG ? imagecreatefrompng($source) : null;
                    break;

                case 'gif':
                    $source_img = $this->bSourceGIF ? imagecreatefromgif($source) : null;
                    break;

                case 'webp':
                    $source_img = $this->bSourceWEBP ? imagecreatefromwebp($source) : null;
                    break;

                case 'wbmp':
                    $source_img = $this->bSourceWEBP ? imagecreatefromwbmp($source) : null;
                    break;

                default:
                    $source_img = null;
                    break;
            }

            if (is_null($source_img))
            {
                trigger_error(sprintf(
                    self::MSG_IMAGETYPE_NOT_SUPPORTED,
                    __METHOD__,
                    $imageType 
                ));
                return false;
            }

            $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
            
            // Allow png transparency (full alpha channel information)
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
            
            imagecopyresampled($dst_img, $source_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $original_x, $original_y);

            $destinationImageType = $this->getFileTypeByPath($destination);

            switch ($destinationImageType)
            {
                case 'jpg':
                case 'jpeg':
                    $fileWritten = $this->bWriteJPG ? imagejpeg($dst_img, $destination, $quality): null; // between 0 and 100
                    break;

                case 'png':
                    $fileWritten = $this->bWritePNG ? imagepng($dst_img, $destination, 8): null; // between 0 and 9
                    break;

                case 'gif':
                    $fileWritten = $this->bWriteGIF ? imagegif($dst_img, $destination): null;
                    break;

                case 'webp':
                    $fileWritten = $this->bWriteWEBP ? imagewebp($dst_img, $destination, $quality): null; // between 0 and 100
                    break;

                case 'wbmp':
                    $fileWritten = $this->bWriteWBMP? imagewbmp($dst_img, $destination): null;
                    break;

                default:
                    $fileWritten = false;
                    break;
            }

            if (!$fileWritten)
            {
                trigger_error(sprintf(
                    self::MSG_IMAGE_DEST_TYPE_NOT_SUPPORTED,
                    __METHOD__,
                    $destinationImageType
                ));
                return false;
            }

            return true;
        } else
        {
            return false;
        }
    }

    public function getFileTypeByPath(string $destinationPath): string
    {
        $destinationPathTerms = explode(".", $destinationPath);
        return strtolower(array_pop($destinationPathTerms) ?? "none");
    }

    public function resize(string $source, int $new_max_w, int $new_max_h, $quality = 75): bool
    {
        // h und w neu auf w berechnen!
        list($orig_w, $orig_h) = getimagesize($source);
        if ($orig_w > $new_max_w)
        {
            $new_w = $new_max_w;
            $new_h = intval($orig_h * ($new_w / $orig_w));
            if ($new_h > $new_max_h)
            {
                $new_h = $new_max_h;
                $new_w = intval($orig_w * ($new_h / $orig_h));
            }
        } else if ($orig_h > $new_max_h)
        {
            $new_h = $new_max_h;
            $new_w = intval($orig_w * ($new_h / $orig_h));
        } else
        {
            // Image cant be downsized
            echo "<div align='center'><p style='color: red;'>Image to small to be downsized!</p></div>";
            return false;
        }
        return $this->make_thumb($source, $source, $new_w, $quality);
    }
}
