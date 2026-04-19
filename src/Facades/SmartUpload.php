<?php

namespace Alareqi\SmartUpload\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alareqi\SmartUpload\SmartUpload
 */
class SmartUpload extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Alareqi\SmartUpload\SmartUpload::class;
    }
}
