# Invoicing
I am actually using this application for my private purposes, but I have decided to release it as freely available to everyone.  
This application is used to record the time spent on individual tasks and to generate invoices and a monthly clock report.

## Technology
Laravel 10 framework ❤️  
TALL stack  
Laravel Breeze  
Laravel Volt  
Laravel Sail  
[Livewire Tables from ramonrietdijk 👏](https://github.com/ramonrietdijk/livewire-tables)  

## Installation
```shell
composer install && 
npm install && 
npm run build && 
php artisan migrate:fresh --seed &&
npm run dev
```

Or if you want to use Laravel Sail:
```shell
./vendor/bin/sail up -d &&
./vendor/bin/sail composer install && 
./vendor/bin/sail npm install && 
./vendor/bin/sail npm run build &&
./vendor/bin/sail artisan migrate:fresh --seed &&
./vendor/bin/sail npm run dev
```

Test account credentials:  
name: `test@example.com`  
password: `password`

## In conclusion 🤔
I will be glad if my code helps someone understand the Laravel framework.  
I do not consider myself to be any expert in the Laravel framework and I will be glad if I also learn new things.
