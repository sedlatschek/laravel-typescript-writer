<?php

namespace Sedlatschek\LaravelTypescriptWriter\Commands;

use Illuminate\Console\Command;

class LaravelTypescriptWriterCommand extends Command
{
    public $signature = 'typescript-writer';

    public $description = 'Write the configured data as typescript';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
