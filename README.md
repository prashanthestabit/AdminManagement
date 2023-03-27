# Admin Management Module

The Admin Management module is a pre-built and maintained module for starting a new web application in Laravel. This module is designed to save time and effort in implementing common admin management features in Laravel projects, while promoting consistency and standardization in module design and implementation.

### Features

The module includes the following features:

1. Auth Management: Login,logout,register,forgot password, change password user in your system.
2. User Management: Create, read, update and delete users in the system.
3. Role Management: Create, read, update and delete roles for users in the system.
4. Permission Management: Create, read, update and delete permissions for roles in the system.

### Requirements
1. Laravel 8.x or higher
2. PHP 7.4 or higher
3. [AdminLTE theme](https://adminlte.io/)
4. [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction)
5. [Nwidart Laravel Module](https://nwidart.com/laravel-modules/v6/introduction)

### Installation
1. Install Laravel on your system.
2. [Install Spatie Laravel Permission and set it up according to the documentation.](#1-spiteLaravel)
3. Install Nwidart laravel and setup according to the documentation.
4. Clone the Admin Management module repository into your Laravel project's Modules directory:

```bash
cd /path/to/your/laravel/project/Modules
git clone https://github.com/example/AdminManagement.git
```

### Enable Module
Run the module enable:

```bash
php artisan module:enable AdminManagement
```

### Publish Public folder

```bash
php artisan vendor:publish --tag=public --provider="Modules\AdminManagement\Providers\AdminManagementServiceProvider"
```

### Run Seed

```bash
php artisan module:seed AdminManagement
```

### Usage
Once the module is installed and set up, you can access the admin management features by navigating to the appropriate URLs:

1. Auth Management: /adminmanagement/login
2. User Management: /users
3. Role Management: /roles
4. Permission Management: /permissions

You can use master.blade.php, and app.blade.php

```bash
@extends('adminmanagement::layouts.app')

@section('content')
    <x-adminmanagement::page-header pageTitle="Creare User" :breadcrumbs="['Home', 'Creare User']" />

@endsection
@push('script')

@endpush
@push('style')

@endpush
```

#### 1. SpiteLaravel

Now we require to install Spatie package for ACL, that way we can use its method. Also we will install form collection package. So Open your terminal and run bellow command.

```bash
composer require spatie/laravel-permission
```

```bash
composer require laravelcollective/html
```

Now open config/app.php file and add service provider and aliase.

config/app.php

```bash
'providers' => [
	....
	Spatie\Permission\PermissionServiceProvider::class,
],
```
We can also custom changes on Spatie package, so if you also want to changes then you can fire bellow command and get config file in config/permission.php and migration files.

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

```

Now you can see permission.php file and one migrations. so you can run migration using following command:
```bash
php artisan migrate

```

#### permission

add middleware in Kernel.php file this way :

app/Http/Kernel.php
```bash
protected $middlewareAliases = [
    ....
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
]
```


### NOTE:- For testing the api you can run the following command

```bash
php artisan test Modules/AdminManagement
```


