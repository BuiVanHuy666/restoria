<?php

namespace App\Services\Core;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\ImageDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    protected ImageManager $manager;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->manager = ImageManager::usingDriver(Driver::class);
    }

    /**
     * @throws ImageDecoderException
     * @throws DriverException
     * @throws InvalidArgumentException
     */
    public function handleUpload(UploadedFile $file, string $path, ?array $cropSize = null, int $quality = 80): string
    {
        $image = $this->manager->decode($file->getRealPath());

        if ($cropSize && isset($cropSize['width']) && isset($cropSize['height'])) {
            $image->resize($cropSize['width'], $cropSize['height']);
        }

        $encodedImage = $image->encode(new WebpEncoder(quality: $quality));

        $filename = Str::uuid() . '.webp';

        $fullPath = $path . $filename;

        Storage::disk('public')->put($fullPath, (string) $encodedImage);

        return $filename;
    }

    public function deleteImage(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
