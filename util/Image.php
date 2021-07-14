<?php

class Image
{
    public static function mimeType(string $path): string | bool
    {
        if (!file_exists($path))
        {
            return false;
        }

        if (($imageType = exif_imagetype($path)) === false)
        {
            return false;
        }

        return image_type_to_mime_type($imageType);
    }
}
