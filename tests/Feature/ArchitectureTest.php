<?php

declare(strict_types=1);

test('app')
    ->expect('App')
    ->toUseStrictTypes();

test('globals')
    ->expect(['dd', 'dump', 'ray', 'die', 'eval', 'sleep', 'debugbar'])
    ->not->toBeUsed();

test('contracts')
    ->expect('App\Contracts')
    ->toBeInterfaces();

test('controllers')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');

test('enums')
    ->expect('App\Enums')
    ->toHaveSuffix('Enum');
