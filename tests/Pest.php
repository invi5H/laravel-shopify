<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Invi5h\LaravelShopify\Tests\TestCase;
use Pest\Expectation;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

echo 'Environment Variable Keys:'.PHP_EOL;
dump(array_keys($_ENV));
dump(array_keys(getenv()));

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class)->in('Unit', 'Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', fn() : Expectation => $this->toBe(1));

expect()->extend('toBeResponse', fn() : Expectation => $this->toBeInstanceOf(Response::class));
expect()->extend('toBeRedirect', fn() : Expectation => $this->toBeInstanceOf(RedirectResponse::class));

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something() : void
{
    // ..
}
