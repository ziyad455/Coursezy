<?php

namespace App\Jobs;

use App\Services\CloudinaryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteCloudinaryVideos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 30;

    /**
     * Create a new job instance.
     *
     * @param array $publicIds Array of Cloudinary public_ids to delete
     * @param int|null $courseId Optional course ID for logging context
     * @param int|null $sectionId Optional section ID for logging context
     */
    public function __construct(
        protected array $publicIds,
        protected ?int $courseId = null,
        protected ?int $sectionId = null
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(CloudinaryService $cloudinaryService): void
    {
        if (empty($this->publicIds)) {
            Log::info('DeleteCloudinaryVideos: No videos to delete', [
                'course_id' => $this->courseId,
                'section_id' => $this->sectionId,
            ]);
            return;
        }

        Log::info('DeleteCloudinaryVideos: Starting deletion', [
            'count' => count($this->publicIds),
            'course_id' => $this->courseId,
            'section_id' => $this->sectionId,
        ]);

        $result = $cloudinaryService->deleteMultipleVideos($this->publicIds);

        if ($result['success']) {
            Log::info('DeleteCloudinaryVideos: All videos deleted successfully', [
                'count' => count($this->publicIds),
                'course_id' => $this->courseId,
                'section_id' => $this->sectionId,
            ]);
        } else {
            Log::warning('DeleteCloudinaryVideos: Some videos failed to delete', [
                'failed' => $result['failed'],
                'course_id' => $this->courseId,
                'section_id' => $this->sectionId,
            ]);

            // If there are failures, we might want to retry only the failed ones
            // For now, we log them for manual cleanup
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('DeleteCloudinaryVideos: Job failed after all retries', [
            'public_ids' => $this->publicIds,
            'course_id' => $this->courseId,
            'section_id' => $this->sectionId,
            'error' => $exception->getMessage(),
        ]);
    }
}
