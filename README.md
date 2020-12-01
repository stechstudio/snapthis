# Laravel SDK for SnapThis snapshot service

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stechstudio/snapthis.svg?style=flat-square)](https://packagist.org/packages/stechstudio/snapthis)

SnapThis is a screenshot service that will convert an URL or HTML payload into a PNG image or PDF file. 

## Installation

You can install the package via composer:

```bash
composer require stechstudio/snapthis
```

Store your API key in the .env file:

```bash
SNAPTHIS_API_KEY=[your api key]
```

## Quickstart

Take a snapshot by using the `snapshot` or `pdf` methods:

```php
use SnapThis;

// Will take a PNG image snapshot
SnapThis::snapshot('https://laravel.com');
```

Return this result from a controller method to redirect to the snapshot, or add the `download` method to force the snapshot to download for your user.

```php
return SnapThis::pdf('https://laravel.com')->download();
```

To get the raw binary contents use the `contents` method:

```php
$contents = SnapThis::pdf('https://laravel.com')->contents();
```

You can pass in an HTML string instead of a URL:

```php
SnapThis::pdf("<strong>Hello there</strong>");
```

You can also pass in a blade view, then simply chain the `snapshot` or `pdf` methods.

```php
$users = User::all();
return SnapThis::view('reports.users', ['users' => $users])->pdf();
```

## Options

There are quite a few options for customizing the snapshot. Documentation coming!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
