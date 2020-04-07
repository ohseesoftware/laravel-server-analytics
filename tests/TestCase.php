<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use OhSeeSoftware\LaravelServerAnalytics\Http\Middleware\LogRequest;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('laravel-server-analytics.user_model', 'Illuminate\Foundation\Auth\User');

        $this->setupDatabase();

        $this->withFactories(__DIR__ . '/factories');
    }

    protected function setupDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('remember_token');
            $table->timestamps();
        });
    }


    protected function getPackageProviders($app)
    {
        return [\OhSeeSoftware\LaravelServerAnalytics\Tests\TestServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware(LogRequest::class);
    }
}
