<?php

namespace Sedlatschek\LaravelTypescriptWriter\Commands;

use Illuminate\Console\Command;
use Sedlatschek\LaravelTypescriptWriter\LaravelTypescriptWriter;

class LaravelTypescriptWriterCommand extends Command
{
    public $signature = 'typescript-writer';

    public $description = 'Write the configured data as typescript';

    public function handle(): int
    {
        $files = config('typescript-writer.files', []);

        foreach ($files as $file) {
            LaravelTypescriptWriter::write($file);
        }

        $this->comment('TypeScript data generated successfully');

        return self::SUCCESS;
    }
}
