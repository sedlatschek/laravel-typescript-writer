<?php

use Sedlatschek\LaravelTypescriptWriter\LaravelTypescriptWriter;
use Sedlatschek\LaravelTypescriptWriter\TypescriptData;
use Sedlatschek\LaravelTypescriptWriter\TypescriptEnum;
use Sedlatschek\LaravelTypescriptWriter\TypescriptFile;

it('writes valid typescript data', function () {
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

it('applies single quote config', function () {
    config()->set('typescript-writer.single_quote', true);

    LaravelTypescriptWriter::write(new TypescriptFile($this->output, [
        new TypescriptData('Object', 'test', [
            'foo' => 'bar',
        ]),
    ]));

    $this->assertOutput(
        'export const test: Object = {
  foo: \'bar\'
};');
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

it('writes valid typescript enums', function () {
    LaravelTypescriptWriter::write(new TypescriptFile($this->output, [
        new TypescriptEnum('UserRole', [
            'Regular' => 1,
            'Analyst' => 2,
            'Admin' => 3,
        ]),
        new TypescriptEnum('UserRole2', [
            'Regular' => 'reg',
            'Analyst' => 'ana',
            'Admin' => 'adm',
        ]),
        new TypescriptEnum('UserRole3', [
            'Regular' => 1,
            'Analyst-OU' => 2,
            'Admin OU' => 3,
        ]),
    ]));

    $this->assertOutput(
        'export const enum UserRole {
  Regular = 1,
  Analyst = 2,
  Admin = 3,
}

export const enum UserRole2 {
  Regular = "reg",
  Analyst = "ana",
  Admin = "adm",
}

export const enum UserRole3 {
  Regular = 1,
  \'Analyst-OU\' = 2,
  \'Admin OU\' = 3,
}');
});
