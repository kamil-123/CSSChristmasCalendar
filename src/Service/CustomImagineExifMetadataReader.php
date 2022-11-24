<?php

namespace App\Service;

use Imagine\Image\Metadata\ExifMetadataReader;

class CustomImagineExifMetadataReader extends ExifMetadataReader
{
    /**
     * Get the reason why this metadata reader is not supported.
     *
     * @return string empty string if the reader is available
     */
    public static function getUnsupportedReason(): string
    {
        if (!function_exists('exif_read_data')) {
            return 'The PHP EXIF extension is required to use the ExifMetadataReader';
        }
        if (!in_array('data', stream_get_wrappers(), true)) {
            return 'The data:// stream wrapper must be enabled';
        }

        // Remove check for allow_url_fopen

        return '';
    }
}