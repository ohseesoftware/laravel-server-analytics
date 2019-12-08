# Laravel Analytics

[![Current Release](https://img.shields.io/github/release/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://github.com/ohseesoftware/laravel-server-analytics/releases)
![Build Status Badge](https://github.com/ohseesoftware/laravel-server-analytics/workflows/Build/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/ohseesoftware/laravel-server-analytics/badge.svg?branch=master)](https://coveralls.io/github/ohseesoftware/laravel-server-analytics?branch=master)
[![Maintainability Score](https://img.shields.io/codeclimate/maintainability/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://codeclimate.com/github/ohseesoftware/laravel-server-analytics)
[![Downloads](https://img.shields.io/packagist/dt/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://packagist.org/packages/ohseesoftware/laravel-server-analytics)
[![MIT License](https://img.shields.io/github/license/ohseesoftware/laravel-server-analytics.svg?style=flat-square)](https://github.com/ohseesoftware/laravel-server-analytics/blob/master/LICENSE)

Server side analytics for your Laravel application or website. No cookies, no tracking :)

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