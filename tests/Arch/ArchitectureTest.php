<?php

declare(strict_types=1);

/**
 * Architecture Tests
 *
 * These tests enforce structural rules and coding standards across the application.
 * They ensure code quality by validating architectural decisions automatically.
 */

// Strict Types Declaration
arch('all source files use strict types')
    ->expect('App')
    ->toUseStrictTypes();

// Laravel Conventions
arch('models extend Eloquent base model')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

arch('controllers extend base controller')
    ->expect('App\Http\Controllers')
    ->toExtend('App\Http\Controllers\Controller')
    ->ignoring('App\Http\Controllers\Controller');

arch('middleware implement interface')
    ->expect('App\Http\Middleware')
    ->toImplement('Illuminate\Contracts\Middleware\Middleware')
    ->ignoring([
        'App\Http\Middleware\TrustProxies',
        'App\Http\Middleware\ValidateSignature',
        'App\Http\Middleware\EncryptCookies',
    ]);

arch('form requests extend base request')
    ->expect('App\Http\Requests')
    ->toExtend('Illuminate\Foundation\Http\FormRequest');

arch('policies follow naming conventions')
    ->expect('App\Policies')
    ->toHaveSuffix('Policy');

arch('jobs implement ShouldQueue')
    ->expect('App\Jobs')
    ->toImplement('Illuminate\Contracts\Queue\ShouldQueue');

arch('exceptions extend base exception')
    ->expect('App\Exceptions')
    ->toExtend('Exception')
    ->ignoring('App\Exceptions\Handler');

// Code Quality
arch('no debug statements in production code')
    ->expect(['dd', 'dump', 'var_dump', 'print_r', 'ray'])
    ->not->toBeUsed();

arch('facades not used in models')
    ->expect('App\Models')
    ->not->toUse('Illuminate\Support\Facades');

// Note: toHaveMethodsCountLessThan is not available in current Pest version
// This rule can be enforced via PHPMD's ExcessivePublicCount rule instead
// arch('avoid god objects - max 10 public methods per class')
//     ->expect('App')
//     ->classes()
//     ->toHaveMaxPublicMethods(10)
//     ->ignoring([
//         'App\Http\Controllers\Controller',
//         'App\Providers',
//     ]);

// Security
arch('database queries use query builder or Eloquent')
    ->expect('App')
    ->not->toUse('DB::raw')
    ->ignoring([
        'App\Providers',
    ]);
