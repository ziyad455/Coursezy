<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Factory;
use Illuminate\Support\Str;

class FirebaseStorageService
{
    private $storage;
    private $bucket;

    public function __construct()
    {
        $factory = (new Factory())
            ->withServiceAccount(config('firebase.projects.default.credentials'));
        
        $this->storage = $factory->createStorage();
        $this->bucket = $this->storage->getBucket(config('firebase.projects.default.storage.default_bucket'));
    }

    /**
     * Upload a video file to Firebase Storage
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array
     */
    public function uploadVideo(UploadedFile $file, $folder = 'course-videos')
    {
        try {
            // Generate unique filename
            $fileName = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $folder . '/' . $fileName;

            // Get file contents
            $fileContents = file_get_contents($file->getRealPath());

            // Upload to Firebase Storage
            $object = $this->bucket->upload($fileContents, [
                'name' => $path,
                'metadata' => [
                    'contentType' => $file->getMimeType(),
                    'metadata' => [
                        'originalName' => $file->getClientOriginalName(),
                        'uploadTime' => now()->toIso8601String(),
                    ]
                ]
            ]);

            // Make the file publicly accessible
            $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);

            // Get the public URL
            $publicUrl = sprintf(
                'https://storage.googleapis.com/%s/%s',
                config('firebase.projects.default.storage.default_bucket'),
                $path
            );

            // Get video duration if possible (requires FFmpeg)
            $duration = $this->getVideoDuration($file);

            return [
                'success' => true,
                'url' => $publicUrl,
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'duration' => $duration,
                'original_name' => $file->getClientOriginalName(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to upload video: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a file from Firebase Storage
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile($path)
    {
        try {
            $object = $this->bucket->object($path);
            $object->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to delete file from Firebase: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get video duration using FFmpeg (if available)
     *
     * @param UploadedFile $file
     * @return int|null Duration in seconds
     */
    private function getVideoDuration(UploadedFile $file)
    {
        try {
            // Check if FFprobe is available
            $ffprobePath = exec('which ffprobe');
            if (empty($ffprobePath)) {
                return null;
            }

            $filePath = $file->getRealPath();
            $command = sprintf(
                '%s -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s',
                $ffprobePath,
                escapeshellarg($filePath)
            );

            $duration = shell_exec($command);
            return $duration ? (int) floor((float) $duration) : null;
        } catch (\Exception $e) {
            \Log::warning('Could not get video duration: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate a signed URL for private content
     *
     * @param string $path
     * @param int $expiresIn Expiration time in minutes
     * @return string
     */
    public function getSignedUrl($path, $expiresIn = 60)
    {
        try {
            $object = $this->bucket->object($path);
            $expires = now()->addMinutes($expiresIn);
            
            return $object->signedUrl($expires);
        } catch (\Exception $e) {
            \Log::error('Failed to generate signed URL: ' . $e->getMessage());
            return '';
        }
    }
}