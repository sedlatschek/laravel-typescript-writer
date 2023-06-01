<?php

/*
Configuration file for sedlatschek/laravel-typescript-writer.
See https://github.com/sedlatschek/laravel-typescript-writer
*/

// use Sedlatschek\LaravelTypescriptWriter\TypescriptData;
// use Sedlatschek\LaravelTypescriptWriter\TypescriptFile;

return [
    // The typescript file indentation
    'indentation' => 2,

    // The end-of-line character
    'eol_char' => PHP_EOL,

    // Whether to use single or double quotes for strings
    'single_quote' => false,

    // The files that should be written
    'files' => [
        /*
        new TypescriptFile(__DIR__.'/example.ts', [
            // The contents of the file
            new TypescriptData('Array<Test>', 'test', [
                [
                    'id' => 33,
                    'name' => 'test',
                    'active' => true,
                    'languages' => [
                        'German',
                        'English',
                    ],
                ],
            ]),
        ]),
        */
    ],
];
