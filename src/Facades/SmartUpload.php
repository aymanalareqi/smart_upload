<?php

namespace Alareqi\SmartUpload\Facades;

use Alareqi\SmartUpload\Support\FileUploader;
use Illuminate\Support\Facades\Facade;

/**
 * @see FileUploader
 */
class SmartUpload extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'smart-upload';
    }
}
