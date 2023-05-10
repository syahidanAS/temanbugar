# Temanbugar Service

## Overview
This repository is the core service of the Temanbugar platform

## Stack Architechture
1. PHP 8, Laravel
2. Mysql or MariaDB
3. Firebase JWT

## Local development quickstart
Clone the repository
```
$ git clone https://github.com/syahidanAS/temanbugar.git
```
Copy the config file and adjust the needs
```
$ copy .env-example .env
```
Generate the APP_KEY
```
$ php artisan key:generate
```
App dependencies using composer
```
$ composer install
```
DB migration
```
$ php artisan migrate
```

Run the local server:
```
$ php artisan serve
```

Your playground already to use:
```
Open on the browser: {APP_URL}/api/auth
```
