# Laravel Analytics

[![Current Release](https://img.shields.io/github/release/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://github.com/ohseesoftware/laravel-server-analytics/releases)
![Build Status Badge](https://github.com/ohseesoftware/laravel-server-analytics/workflows/Build/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/ohseesoftware/laravel-server-analytics/badge.svg?branch=master)](https://coveralls.io/github/ohseesoftware/laravel-server-analytics?branch=master)
[![Maintainability Score](https://img.shields.io/codeclimate/maintainability/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://codeclimate.com/github/ohseesoftware/laravel-server-analytics)
[![Downloads](https://img.shields.io/packagist/dt/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://packagist.org/packages/ohseesoftware/laravel-server-analytics)
[![MIT License](https://img.shields.io/github/license/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://github.com/ohseesoftware/laravel-server-analytics/blob/master/LICENSE)

Server side analytics for your Laravel application or website. No cookies, no tracking :)

## Vision

There are a lot of choices out there when it comes to analytics for your web project. With the progress of [GDPR](https://en.wikipedia.org/wiki/General_Data_Protection_Regulation), a focus on privacy has emerged. Consumers are now learning that they can be tracked across the web, and are not appreciative of this.

As you develop your next website or project, you may wish to add analytics so you can see how many pageviews, etc you're getting. You may not care to track how a specific visitor got to your website, or the actions they took once they were there. You just want some basic metrics on the activity on your web project.

You've come to the right place.

### Goals

-   [x] Handled entirely by the backend: this means no client-side JS to add to your site, which means no impact on performance for your users
-   [x] Write operations should not impact request performance
-   [x] Customizable: we'll track the main aspects of a request (path, status, params, duration, user, etc), but also allow you to attach extra data to a request (such as which entity in your database was accessed or metadata)
-   [ ] Ships with a default dashboard to view your data: we'll give you a default dashboard (and seperate route) to view your data
-   [ ] Accessible API: in addition to a default dashboard, we'll expose an API you can use to pull custom metrics out of your data

## Installation

You can install the package via composer:

```bash
composer require ohseesoftware/laravel-server-analytics
```

Publish the package (config, assets, etc):

```bash
php artisan vendor:publish --provider="OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsServiceProvider"
```

Review the configuration file and make changes as necessary:

```bash
config/laravel-server-analytics.php
```

Run database migrations:

```bash
php artisan migrate
```

## Usage

### Basic Usage

For basic request tracking, there's no custom code you need to add to your application other than including the middleware:

```php
// Kernel.php

protected $middleware = [
  // ... other middlware here
  \OhSeeSoftware\LaravelServerAnalytics\Http\Middleware\LogRequest::class,
];
```

If you want to only track a specific middleware group, add it to that group instead of the global `$middleware` variable.

### Tracking authenticated user

**v3.0.0+**

By default, the package will automatically track the authenticated user who made the request. This is stored directly in the `analytics` table in the `user_id` column.

The default migration assumes you users are stored in a `users` table with a `BIGINT` field type for the primary key. If your users are not stored like that, you should write your own migration. The package also assumes your `User` model is located at `App\User`. If yours is located in a difference namespace, you can update the `user_model` key in the published config file.

When logging a request, the package will use this code to insert the `user_id` into the `analytics` table:

```php
if ($user = $request->user()) {
    $userId = $user->id;
}
```

### Excluding Routes

If you'd like to exclude specific routes (or wildcards) from being tracked, you can do so via the `addRouteExclusions()` method:

```php
// AppServiceProvider

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

public function boot()
{
    // Do not track `/home` or `/admin/*` routes
    ServerAnalytics::addRouteExclusions([
      '/home',
      '/admin/*'
    ]);
}
```

### Excluding Request Methods

If you'd like to exclude specific request methods from being tracked, you can do so via the `addMethodExclusions()` method:

```php
// AppServiceProvider

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

public function boot()
{
    // Do not track `POST` or `PUT` requests
    ServerAnalytics::addMethodExclusions(['POST', 'PUT']);
}
```

### Excluding Requests From Bots

If you'd like to exclude requests from bots/crawlers, you can set the `ignore_bot_requests` configuration value to `true`.

We use the [Crawl Detect](https://github.com/JayBizzle/Crawler-Detect) package to detect bots/crawlers.

```php
// config/laravel-server-analytics.php

return [
    'ignore_bot_requests' => true
];
```

### Post Request Hooks

We provide an optional hook you can use to run custom logic after an analytics record has been created. You can provide as many hooks as you want, calling `addPostHook` will add a new hook rather than replace existing hooks.

The hook closure is based an instance of `RequestDetails` and the `Analytics` record that was created for the request. You can access both the request and response inside the `RequestDetails` class. If you're using a custom `RequestDetails` class, you'll be given an instance of your custom class.

```php
// AppServiceProvider

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

public function boot()
{
    ServerAnalytics::addPostHook(
        function (RequestDetails $requestDetails, Analytics $analytics) {
            // Do whatever you want with the request, response, and created analytics record
        }
    );
}
```

### Attach Entities to Analytics Records

If you want to attach your application's entities to an analytics record, you can use the `addRelation(Model $model, ?string $reason = null)` on the Analytics model in combination with a hook:

```php
// AppServiceProvider

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

public function boot()
{
    ServerAnalytics::addPostHook(
        function (RequestDetails $requestDetails, Analytics $analytics) {
            // Attach the logged-in user to the analytics request record
            if ($user = $requestDetails->request->user()) {
              $analytics->addRelation($user);
            }
        }
    );
}
```

There's also a helper method available if you want to attach a relation without checking the request, response, or analytics record:

```php
// Controller

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

public function __invoke(Post $post)
{
    ServerAnalytics::addRelation($post);

    // ...finish controller logic
}
```

The method provides an optional second argument, `$reason`, which allows you to add a reason for the relation attachment. The `reason` column is a `string, VARCHAR(255)` column. If not passed, the value will be `null`.

### Attach Metadata to Analytics Records

In addition to attaching entities to your analytics records, you can attach custom metadata (key/value).

```php
// AppServiceProvider

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

public function boot()
{
    ServerAnalytics::addPostHook(
        function (RequestDetails $requestDetails, Analytics $analytics) {
            $analytics->addMeta('foo', 'bar');
        }
    );
}
```

There's also a helper method available if you want to attach metadata without checking the request, response, or analytics record:

```php
// Controller

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

public function __invoke(Post $post)
{
    ServerAnalytics::addMeta('some-key', 'some-value');

    // ...finish controller logic
}
```

### Providing Custom Request Details

We provide sensible defaults for pulling details for the request. However, if you need to pull the details in a different manner, you can provide a custom implementation of the `RequestDetails` class:

```php

// CustomRequestDetails.php

use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class CustomRequestDetails extends RequestDetails
{
    public function getMethod(): string
    {
        return 'TEST';
    }
}

// AppServiceProvider

use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

public function boot()
{
    ServerAnalytics::setRequestDetails(new CustomRequestDetails);
}
```

With the above example, the `method` variable for every request will be set to "TEST".

### Querying Analytics Records

You can use the `AnalyticsRepository` to query data out of the analytics tables. If you need to build a custom query, you can use the `query()` method on the repository instance.

```php

use OhSeeSoftware\LaravelServerAnalytics\Repositories\AnalyticsRepository;

public function loadAnalytics(AnalyticsRepository $analytics)
{
    $records = $analytics->query()->where('method', 'GET')->get();
}
```

There's also a couple query scopes setup on the `Analytics` model.

Filter Analytics records that are related to the a given model:

```php
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

$analytics = Analytics::relatedTo($user);
```

Filter Analytics records that have metadata with a given key:

```php
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

$analytics = Analytics::hasMeta('foo');
```

Filter Analytics records that have a given metadata key/value pair:

```php
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

$analytics = Analytics::withMetaValue('foo', 'bar');
```

## Testing

```bash
./vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email security@ohseesoftware.com instead of using the issue tracker.

## Credits

-   [Owen Conti](https://github.com/ohseesoftware)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
