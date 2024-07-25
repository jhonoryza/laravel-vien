<h1 align="center">Laravel Vien</h1>
<p align="center">
    <a href="https://packagist.org/packages/jhonoryza/laravel-vien">
        <img src="https://poser.pugx.org/jhonoryza/laravel-vien/d/total.svg" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/jhonoryza/laravel-vien">
        <img src="https://poser.pugx.org/jhonoryza/laravel-vien/v/stable.svg" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/jhonoryza/laravel-vien">
        <img src="https://poser.pugx.org/jhonoryza/laravel-vien/license.svg" alt="License">
    </a>
</p>

this package is a starter kit for laravel breeze with stack inertia vue

this package provides laravel inertia vue components

## Available Component

- Table
- Pagination
- Sidebar
- Toast
- ToogleDarkMode
- Checkbox
- Loading

more you can check in `stubs/inertia-vue/Vien` directory or `resources/js/Components/Vien` after vien installed

<p float="left">
  <img src="/public/sc1.png" width="300" />
  <img src="/public/sc2.png" width="300" /> 
</p>
<p float="left">
  <img src="/public/sc3.png" width="300" />
  <img src="/public/sc4.png" width="300" />
</p>
<p float="left">
  <img src="/public/sc5.png" width="300" />
  <img src="/public/sc6.png" width="300" />
</p>

## Requirement

- PHP >= 8.2
- Laravel >= 10
- Laravel [Breeze Stack Inertia Vue](https://laravel.com/docs/11.x/starter-kits#breeze-and-inertia)

## Getting Started

package installation

```bash
composer require --dev jhonoryza/laravel-vien
```

vien installation

```bash
php artisan vien:install
```

resource generator

before using this generator, make sure you have already migration and models

```bash
php artisan vien:generate table-name
```

optional to publish config

```bash
php artisan vendor:publish --tag='vien-config'
```

optional to publish generator stubs

```bash
php artisan vendor:publish --tag='vien-stubs'
```

this will copy all vien component scaffolding

after installed successfully you can remove this package from your dev requirement

### Security

If you've found a bug regarding security please mail [jardik.oryza@gmail.com](mailto:jardik.oryza@gmail.com) instead of
using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.