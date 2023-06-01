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

    $this->assertEquals(
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
];",
        $this->getOutput(),
    );
});
