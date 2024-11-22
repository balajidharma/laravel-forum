<h1 align="center">Laravel Forum</h1>
<h3 align="center">Manage Forum to your Laravel projects.</h3>
<p align="center">
<a href="https://packagist.org/packages/balajidharma/laravel-forum"><img src="https://poser.pugx.org/balajidharma/laravel-forum/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/balajidharma/laravel-forum"><img src="https://poser.pugx.org/balajidharma/laravel-forum/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/balajidharma/laravel-forum"><img src="https://poser.pugx.org/balajidharma/laravel-forum/license" alt="License"></a>
</p>

## Table of Contents

- [Installation](#installation)
- [Demo](#demo)

## Installation
- Install the package via composer
```bash
composer require balajidharma/laravel-forum
```
- Publish the migration and the config/forum.php config file with
```bash
php artisan vendor:publish --provider="BalajiDharma\LaravelForum\ForumServiceProvider"
```
- Run the migrations
```bash
php artisan migrate
```

## Demo
The "[Basic Laravel Admin Penel](https://github.com/balajidharma/basic-laravel-admin-panel)" starter kit come with Laravel Category
