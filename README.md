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

* [ ] Handled entirely by the backend: this means no client-side JS to add to your site, which means no impact on performance for your users
* [ ] Write operations are handled in an asynchronous job: tracking page views shouldn't impact request performance
* [ ] Ships with a default dashboard to view your data: we'll give you a default dashboard (and seperate route) to view your data
* [ ] Customizable: we'll track the main aspects of a request (path, status, params, duration, user, etc), but also allow you to attach extra data to a request (such as which entity in your database was accessed)
* [ ] Accessible API: in addition to a default dashboard, we'll expose an API you can use to pull custom metrics out of your data

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

`config/laravel-server-analytics.php`

Run database migrations:

```bash
php artisan migrate
```

## Usage

``` php
// Usage description here
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email security@ohseesoftware.com instead of using the issue tracker.

## Credits

- [Owen Conti](https://github.com/ohseesoftware)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
