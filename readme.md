# API

Made with the powerful, elegant Laravel Framework !

## Features

  - OAuth 2.0
  - Auth (login, register, forgot password, mail confirmation)
  - Categories
  - Items
  - Bookmarks
  - ElasticSearch

## Installation

```sh
git clone https://github.com/Clement3/api-laravel.git directory
cd directory
cp .env.example .env
composer install
php artisan migrate
php artisan passport:install
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
php artisan vendor:publish --provider="Barryvdh\Cors\ServiceProvider"
```

## Documentation

| Method | URL | PARAMS |
| ------ | ------ | ------ |
| POST | /oauth/token | String (grant_type) - Int (client_id) - String (client_secret) - String (username) - String (password)
