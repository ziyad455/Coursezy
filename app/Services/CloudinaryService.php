<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Delete a single video from Cloudinary.
     *
     * @param string $publicId The Cloudinary public_id of the video
     * @return array ['success' => bool, 'message' => string]
     */
    public function deleteVideo(string $publicId): array
    {
        try {
            // Use the cloudinary helper function to access the SDK directly
            $cloudinary = cloudinary();
            $result = $cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => 'video',
                'invalidate' => true, // Invalidate CDN cache
            ]);

            // The result is an array with 'result' key
            $status = $result['result'] ?? 'unknown';

            if ($status === 'ok' || $status === 'not found') {
                Log::info("Cloudinary video deleted successfully", [
                    'public_id' => $publicId,
                    'result' => $status
                ]);

                return [
                    'success' => true,
                    'message' => $status === 'not found'
                        ? 'Video was already deleted from Cloudinary'
                        : 'Video deleted successfully'
                ];
            }

            Log::warning("Cloudinary video deletion returned unexpected status", [
                'public_id' => $publicId,
                'result' => $status
            ]);

            return [
                'success' => false,
                'message' => "Unexpected result: {$status}"
            ];

        } catch (\Exception $e) {
            Log::error("Failed to delete video from Cloudinary", [
                'public_id' => $publicId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete multiple videos from Cloudinary.
     *
     * @param array $publicIds Array of Cloudinary public_ids
     * @return array ['success' => bool, 'results' => array, 'failed' => array]
     */
    public function deleteMultipleVideos(array $publicIds): array
    {
        $results = [];
        $failed = [];

        foreach ($publicIds as $publicId) {
            $result = $this->deleteVideo($publicId);
            $results[$publicId] = $result;

            if (!$result['success']) {
                $failed[] = $publicId;
            }
        }

        return [
            'success' => empty($failed),
            'results' => $results,
            'failed' => $failed
        ];
    }

    /**
     * Extract Cloudinary public_id from a lesson's metadata.
     *
     * @param array|null $metadata The lesson metadata
     * @return string|null The public_id or null if not found
     */
    public static function extractPublicIdFromMetadata(?array $metadata): ?string
    {
        return $metadata['cloudinary_public_id'] ?? null;
    }
}
