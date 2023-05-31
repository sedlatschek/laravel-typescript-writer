<?php

namespace Sedlatschek\LaravelTypescriptWriter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sedlatschek\LaravelTypescriptWriter\Commands\LaravelTypescriptWriterCommand;

class LaravelTypescriptWriterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-typescript-writer')
            ->hasConfigFile()
            ->hasCommand(LaravelTypescriptWriterCommand::class);
    }
}
