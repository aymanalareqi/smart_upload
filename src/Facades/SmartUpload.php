<?php

namespace Alareqi\SmartUpload\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alareqi\SmartUpload\Support\FileUploader
 */
class SmartUpload extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'smart-upload';
    }
}