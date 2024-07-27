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

This package is a starter kit for laravel breeze with inertia vue stack, so it requires laravel [breeze and inertia vue stack](https://laravel.com/docs/11.x/starter-kits#breeze-and-inertia)

Laravel vien provides laravel inertia vue components, when you install this package, it will generate all the components in `resources/js/Components/Vien` directory. 

No magic behind the scene, you can customize as much as you want

## Features

- Table component with search, sort, filter, pagination, toggle, select and bulk action
- Resource Generator to quickly generate CRUD
- Toggle dark mode and light mode component
- Responsive sidebar component
- Flash message using toast component
- Loading spinner component
- [SelectSimple](./docs/select-simple.md) component
- InputDateTime component using flatpickr
- TextArea compoonent
- Toggle switch component

for available components you can check in `stubs/inertia-vue/Vien` directory or `resources/js/Components/Vien` after vien installed. 

here is some screenshot:

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

### Package installation

```bash
composer require --dev jhonoryza/laravel-vien
```

### Vien installation

```bash
php artisan vien:install
```

### Optional to update vien components

```bash
php artisan vien:update
```

### Optional to use resource generator

before using this generator, make sure you have already your new migration and models, example: lets make a `posts` table, then run `artisan migrate`

then you can run the following command to generate the resource

```bash
php artisan vien:generate posts
```

After successfully generating the resource above, then adjust

`resources/js/Components/Vien/Utils/menu-items.js` file to add your `new navigation menu`,

for example:

```js
import { IconHome } from "@tabler/icons-vue";

//`id value must be unique`
export const menuItems = [
  {
    id: 4,
    title: "Post",
    routeName: "posts",
    component: "Post",
    icon: IconHome
  }
]
```

then run `npm run dev` to rebuild

### Optional to publish config

```bash
php artisan vendor:publish --tag='vien-config'
```

### Optional to publish generator stubs

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