<?php

namespace Alareqi\SmartUpload\Commands;

use Illuminate\Console\Command;

class SmartUploadCommand extends Command
{
    public $signature = 'smart-upload';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
