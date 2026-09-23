<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resizes an uploaded image down to a sane maximum width and re-encodes it
 * at a reasonable quality before storing it, so a 4000px phone-camera photo
 * doesn't ship to every visitor at full resolution. Falls back to storing
 * the file untouched if GD can't decode it for any reason.
 */
class ImageOptimizerService
{
    public function store(UploadedFile $file, string $directory, string $disk = 'public', int $maxWidth = 1920, int $quality = 82): string
    {
        $info = @getimagesize($file->getRealPath());

        if (! $info) {
            return $file->store($directory, $disk);
        }

        [$width, $height, $type] = $info;

        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => @imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_GIF => @imagecreatefromgif($file->getRealPath()),
            IMAGETYPE_BMP => @imagecreatefrombmp($file->getRealPath()),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file->getRealPath()),
            default => null,
        };

        if (! $source) {
            return $file->store($directory, $disk);
        }

        $isTranslucent = in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true);

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($maxWidth, $newHeight);

            if ($isTranslucent) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        $extension = match ($type) {
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_GIF => 'gif',
            IMAGETYPE_BMP => 'bmp',
            IMAGETYPE_WEBP => 'webp',
            default => 'jpg',
        };

        ob_start();
        match ($type) {
            IMAGETYPE_PNG => imagepng($source, null, 6),
            IMAGETYPE_GIF => imagegif($source),
            IMAGETYPE_BMP => imagebmp($source),
            IMAGETYPE_WEBP => imagewebp($source, null, $quality),
            default => imagejpeg($source, null, $quality),
        };
        $contents = ob_get_clean();

        imagedestroy($source);

        $path = trim($directory, '/').'/'.Str::random(40).'.'.$extension;

        Storage::disk($disk)->put($path, $contents);

        return $path;
    }
}
