<p align="center">
<img src="https://media-01.imu.nl/storage/ridderenhertog.nl/2163/renh-kassasystemen-weegschalen.jpg" alt="RenH PHP SDK" height="100">
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1969px-Laravel.svg.png" alt="PHP" height="50">
</p>

<p align="center">
<a href="https://github.com/masmerise/de-ridder-den-hertog-for-laravel/actions"><img src="https://github.com/masmerise/de-ridder-den-hertog-for-laravel/actions/workflows/test.yml/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/masmerise/de-ridder-den-hertog-for-laravel"><img src="https://img.shields.io/packagist/dt/masmerise/de-ridder-den-hertog-for-laravel" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/masmerise/de-ridder-den-hertog-for-laravel"><img src="https://img.shields.io/packagist/v/masmerise/de-ridder-den-hertog-for-laravel" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/masmerise/de-ridder-den-hertog-for-laravel"><img src="https://img.shields.io/packagist/l/masmerise/de-ridder-den-hertog-for-laravel" alt="License"></a>
</p>

## Laravel adapter for De Ridder & Den Hertog SDK

This package provides convenient access to the [De Ridder & Den Hertog SDK](https://github.com/masmerise/de-ridder-den-hertog-php-sdk) using the Laravel framework.

## Installation

You can install the package via [composer](https://getcomposer.org):

```bash
composer require masmerise/de-ridder-den-hertog-for-laravel
```

After that, define your `renh` credentials inside the `config/services.php` configuration file:

```php
'renh' => [
    'api_guid' => env('RENH_API_GUID'),
],
```

## Usage

```php
$functions = renh()->getApiFunctions();
```

```php
$renh = app('renh');

$functions = $renh->getApiFunctions();
```

```php
use DeRidderDenHertog\Core\Type\Parameter\Filter;
use DeRidderDenHertog\DeRidderDenHertog;

final readonly class CustomerController
{
    private function __construct(private DeRidderDenHertog $renh) {}
    
    public function show(string $id): void
    {
        [$customer] = $renh->getCustomers(
            filter: Filter::fromSql("Klantnummer={$id}"),
        );
    
        return view('customer.show', ['customer' => $customer]);
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email support@masmerise.be instead of using the issue tracker.

## Credits

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.