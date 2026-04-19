<?php

namespace Alareqi\SmartUpload\Jobs;

use Alareqi\SmartUpload\Models\TemporaryUpload;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredUploads extends Command implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $signature = 'smart-upload:cleanup';

    protected $description = 'Clean up expired temporary uploads';

    public function handle(): int
    {
        $expiredUploads = TemporaryUpload::where('expires_at', '<', now())->get();

        $count = 0;

        foreach ($expiredUploads as $upload) {
            try {
                Storage::disk($upload->disk)->delete($upload->path);
                $upload->delete();
                $count++;
            } catch (\Exception $e) {
                report($e);
            }
        }

        $this->info("Cleaned up {$count} expired temporary uploads.");

        return $count;
    }
}
