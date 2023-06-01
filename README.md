# This is my package laravel-typescript-writer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sedlatschek/laravel-typescript-writer.svg?style=flat-square)](https://packagist.org/packages/sedlatschek/laravel-typescript-writer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sedlatschek/laravel-typescript-writer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sedlatschek/laravel-typescript-writer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sedlatschek/laravel-typescript-writer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sedlatschek/laravel-typescript-writer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sedlatschek/laravel-typescript-writer.svg?style=flat-square)](https://packagist.org/packages/sedlatschek/laravel-typescript-writer)

Write typescript data out of laravel. Does not generate interfaces. Eg.:

```php
$simon = [
    'name' => 'Simon'
]
```

will turn into

```typescript
export const simon: Person = {
    name: "Simon"
}
```

## Installation

You can install the package via composer:

```bash
composer require --dev sedlatschek/laravel-typescript-writer
```

You should publish the config file with:

```bash
php artisan vendor:publish --tag="typescript-writer-config"
```

This is the contents of the published config file:

```php
return [
    // The typescript file indentation
    'indentation' => 2,

    // The end-of-line character
    'eol_char' => PHP_EOL,

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
```

## Configuration

### Value Replacements

You can replace given values with hardcoded strings. This can be useful if you have an enum that can be matched with the output values.

```php
new TypescriptData(
    'Array<Test>',
    'test',
    // the data
    [
        [
            'test' => [
                'flags' => [
                    1,
                    2,
                    3,
                ],
            ],
        ],
    ],
    // the configured replacements
    [
        '*.test.flags' => [
            1 => 'SomeEnum.ABC',
            2 => 'SomeEnum.DEF',
            3 => 'SomeEnum.GHJ',
        ],
    ]),
```

will output

```typescript
export const test: Array<Test> = [
  {
    test: {
      flags: [
        SomeEnum.ABC,
        SomeEnum.DEF,
        SomeEnum.GHJ
      ]
    }
  }
];
```

## Usage

```sh
php artisan typescript-writer
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Simon Sedlatschek](https://github.com/sedlatschek)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
