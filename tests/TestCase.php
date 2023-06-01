<?php

namespace Sedlatschek\LaravelTypescriptWriter\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sedlatschek\LaravelTypescriptWriter\LaravelTypescriptWriterServiceProvider;
use Sedlatschek\LaravelTypescriptWriter\TypescriptFile;

class TestCase extends Orchestra
{
    /**
     * The temporary file to write outputs to.
     */
    protected string $output;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->output = @tempnam('/tmp', 'test.ts');
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        if (file_exists($this->output)) {
            unlink($this->output);
        }

        parent::tearDown();
    }

    /**
     * Get the output contents with file headers.
     */
    protected function getOutput(): string
    {
        $content = str_replace(
            TypescriptFile::getHeader(),
            '',
            file_get_contents($this->output)
        );

        $content = str_replace(
            "\r\n",
            "\n",
            $content,
        );

        return $content;
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTypescriptWriterServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('typescript-writer.eol_char', "\n");
    }
}
