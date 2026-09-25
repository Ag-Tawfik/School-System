<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mcamara\LaravelLocalization\LaravelLocalization;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Locale prefix the localized routes are registered under.
     *
     * The route file calls LaravelLocalization::setLocale() while the
     * application boots, so the locale has to be known before setUp().
     */
    protected $locale = 'en';

    protected function setUp(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY . '=' . $this->locale);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);

        parent::tearDown();
    }

    protected function url(string $path): string
    {
        return '/' . $this->locale . '/' . ltrim($path, '/');
    }
}
