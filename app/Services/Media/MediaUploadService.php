<?php

namespace App\Services\Media;

use App\Models\MediaFile;
use Cloudinary\Cloudinary;

class MediaUploadService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
    }

    public function upload($file): MediaFile
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'resource_type' => 'auto',
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ]
        );

        return MediaFile::create([
            'cloudinary_public_id' => $result['public_id'],
            'url' => $result['secure_url'],
            'type' => $result['resource_type'],
        ]);
    }
}
