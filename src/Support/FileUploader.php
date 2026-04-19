<?php

namespace Alareqi\SmartUpload\Support;

use Alareqi\SmartUpload\Models\TemporaryUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploader
{
    protected string $tempDisk;

    protected string $tempDirectory;

    protected int $expirationHours;

    protected int $maxFileSize;

    protected array $allowedMimes;

    public function __construct()
    {
        $config = config('smart-upload');

        $this->tempDisk = $config['temporary_file_upload']['disk'] ?? 'local';
        $this->tempDirectory = $config['temporary_file_upload']['directory'] ?? 'tmp';
        $this->expirationHours = $config['expiration_hours'] ?? 24;
        $this->maxFileSize = $config['max_file_size'] ?? 10240;
        $this->allowedMimes = $config['allowed_mimes'] ?? ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    }

    public function init(array $data): array
    {
        $filename = $data['filename'] ?? 'file';

        $uuid = (string) Str::uuid();

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $storedFilename = $uuid.'.'.$extension;

        $expiresAt = now()->addHours($this->expirationHours);

        $upload = TemporaryUpload::create([
            'uuid' => $uuid,
            'original_name' => $filename,
            'mime_type' => null,
            'size' => 0,
            'path' => $this->tempDirectory.'/'.$storedFilename,
            'disk' => $this->tempDisk,
            'expires_at' => $expiresAt,
        ]);

        $disk = Storage::disk($this->tempDisk);

        if ($this->tempDisk === 's3') {
            $uploadUrl = $disk->temporaryUrl(
                $upload->path,
                $expiresAt,
                ['Content-Type' => 'application/octet-stream']
            );
        } else {
            $uploadUrl = $disk->path($upload->path);
            $uploadUrl .= '?token='.$uuid;
        }

        return [
            'uuid' => $uuid,
            'upload_url' => $uploadUrl,
            'finished_url' => '/api/upload/'.$uuid.'/finish',
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    public function finish(string $uuid): bool
    {
        $upload = TemporaryUpload::where('uuid', $uuid)->first();

        if (! $upload) {
            return false;
        }

        $file = Storage::disk($upload->disk)->get($upload->path);

        if ($file) {
            $tempFile = tempnam(sys_get_temp_dir(), 'upload_');
            file_put_contents($tempFile, $file);

            $uploadedFile = new UploadedFile(
                $tempFile,
                $upload->original_name,
                mime_content_type($tempFile),
                null,
                true
            );

            $upload->mime_type = $uploadedFile->getMimeType();
            $upload->size = $uploadedFile->getSize();
            $upload->expires_at = now()->addHours($this->expirationHours);
            $upload->save();

            @unlink($tempFile);
        }

        return true;
    }

    public function cancel(string $uuid): bool
    {
        $upload = TemporaryUpload::where('uuid', $uuid)->first();

        if (! $upload) {
            return false;
        }

        Storage::disk($upload->disk)->delete($upload->path);
        $upload->delete();

        return true;
    }

    public function cancelForm(string $formToken): bool
    {
        $uploads = TemporaryUpload::where('form_token', $formToken)->get();

        foreach ($uploads as $upload) {
            Storage::disk($upload->disk)->delete($upload->path);
            $upload->delete();
        }

        return true;
    }

    public function convert(string $uuid, string $directory, ?string $filename = null): string
    {
        $upload = TemporaryUpload::where('uuid', $uuid)->first();

        if (! $upload) {
            throw new \RuntimeException("Temporary upload not found: {$uuid}");
        }

        $originalName = $upload->original_name;
        $newFilename = $filename ?? $originalName;

        $path = $directory.'/'.$newFilename;

        $disk = config('smart-upload.disk', 'local');

        Storage::disk($disk)->writeStream(
            $path,
            Storage::disk($upload->disk)->readStream($upload->path)
        );

        Storage::disk($upload->disk)->delete($upload->path);
        $upload->delete();

        return $path;
    }

    public function getUpload(string $uuid): ?TemporaryUpload
    {
        return TemporaryUpload::where('uuid', $uuid)->first();
    }
}
