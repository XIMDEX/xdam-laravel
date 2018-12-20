<?php

namespace Dam\Core;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class GenerateThumbnail
{
    public static $thumbnails = [
        'medium' => [1800, 1800],
        'mini' => [512, 512]
    ];

    public static function create($filename, $pathFile, $type, $mimeType, $storage, array $thumbnails = null)
    {
        try {
            if (($type == 'image' && $mimeType != 'image/svg') || $type == 'pdf') {
                if ($type == 'image') {
                    $img = Image::make($pathFile);
                } elseif ($type == 'pdf') {
                    $imagick = new \Imagick($pathFile . '[0]');
                    $imagick->setImageFormat('jpg');
                    $imagick->setImageAlphaChannel(11);
                    $img = Image::make($imagick->getImageBlob());
                }

                foreach ($thumbnails ?? static::$thumbnails as $name => $dim) {
                    $thum = $img->resize($dim[0], $dim[1], function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $thum->save("{$storage}/{$name}_{$filename}");
                }
            } else {
                Log::warning("Thumbnail can not be created to {$type} with MIME type {$mimeType}");
            }
        } catch (\Exception | \Error $ex) {
            Log::error($ex->getMessage());
        }
    }
}
