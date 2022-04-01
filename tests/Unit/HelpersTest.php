<?php

use Illuminate\Support\Str;

it('trims collections', function () : void {
    $expected = [
            'foo',
            'bar',
            'baz',
    ];
    $collection = collect([
            'foo ',
            ' bar',
            ' baz',
    ]);
    expect($collection->trim()->toArray())->toBe($expected);
});

it('trims collections recursively', function () : void {
    $expected = [
            'foo' => [
                    'abcd',
            ],
            'bar' => [
                    'abcd',
            ],
            'baz',
            'pqr' => 5.5,
    ];
    $collection = collect([
            'foo' => collect([
                    ' abcd',
            ]),
            'bar' => [
                    ' abcd',
            ],
            ' baz',
            'pqr' => 5.5,
    ]);
    expect($collection->trim()->toArray())->not()->toBe($expected);
    expect($collection->trimRecursive()->toArray())->toBe($expected);
});

it('maps keys', function () : void {
    $expected = [
            'foo' => 'bar',
            'bar' => 'baz',
    ];
    $collection = collect([
            'Foo' => 'bar',
            'baR' => 'baz',
    ]);
    expect($collection->mapKeys([Str::class, 'lower'])->toArray())->toBe($expected);
});
