<?php

use Sedlatschek\LaravelTypescriptWriter\LaravelTypescriptWriter;
use Sedlatschek\LaravelTypescriptWriter\TypescriptData;
use Sedlatschek\LaravelTypescriptWriter\TypescriptFile;

it('writes valid typescript', function () {
    LaravelTypescriptWriter::write(new TypescriptFile($this->output, [
        new TypescriptData('Array<Test>', 'test', [
            [
                'id' => 33,
                'melting_point' => 12.3,
                'name' => 'test',
                'usage' => null,
                'available-languages' => [
                    'German',
                    'English',
                ],
                'flags' => [
                    'active' => true,
                    'hidden' => false,
                ],
                'titles' => collect(['Gold', 'Silver']),
            ],
            ['asdf' => true],
        ]),
    ]));

    $this->assertOutput(
        "export const test: Array<Test> = [
  {
    id: 33,
    melting_point: 12.3,
    name: \"test\",
    usage: null,
    'available-languages': [
      \"German\",
      \"English\"
    ],
    flags: {
      active: true,
      hidden: false
    },
    titles: [
      \"Gold\",
      \"Silver\"
    ]
  },
  {
    asdf: true
  }
];");
});

it('applies indentation config', function () {
    config()->set('typescript-writer.indentation', 4);

    LaravelTypescriptWriter::write(new TypescriptFile($this->output, [
        new TypescriptData('Array<Test>', 'test', [
            [
                'flags' => [
                    'active' => true,
                    'hidden' => false,
                ],
            ],
        ]),
    ]));

    $this->assertOutput(
        'export const test: Array<Test> = [
    {
        flags: {
            active: true,
            hidden: false
        }
    }
];');
});

it('supports value replacement', function () {
    LaravelTypescriptWriter::write(new TypescriptFile($this->output, [
        new TypescriptData('Array<Test>', 'test', [
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
            [
                '*.test.flags' => [
                    1 => 'SomeEnum.ABC',
                    2 => 'SomeEnum.DEF',
                    3 => 'SomeEnum.GHJ',
                ],
            ]),
    ]));

    $this->assertOutput(
        'export const test: Array<Test> = [
  {
    test: {
      flags: [
        SomeEnum.ABC,
        SomeEnum.DEF,
        SomeEnum.GHJ
      ]
    }
  }
];');
});
