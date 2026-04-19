<?php

namespace Alareqi\SmartUpload;

use Alareqi\SmartUpload\Commands\SmartUploadCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Alareqi\SmartUpload\Jobs\CleanupExpiredUploads;
use Alareqi\SmartUpload\Support\FileUploader;

class SmartUploadServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('smart-upload')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_smart_upload_table')
            ->hasRoutes()
            ->hasCommand(CleanupExpiredUploads::class);
    }

    public function register(): void
    {
        parent::register();

        $this->app->singleton('smart-upload', function ($app) {
            return new FileUploader();
        });
    }
}
