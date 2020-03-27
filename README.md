# Laravel API for shopping platform 
> A REST API which allows users add items to shopping basket (cart) and make order

## Description
This project was built with Laravel and MySQL.

## Project Features

##### Authentication: 
- Laravel Passport

##### Cart:
- Create cart and add products to cart
- Remove product from cart
- Create orders from cart

##### Integration testing :
- PHPUnit (https://phpunit.de)
- Faker (https://github.com/fzaninotto/Faker)

## Project Scoping 
- Users (Registered / Guest) can add products to cart and make orders
- Users are allowed to create multiple carts
- Maximum quantity allowed per product is 20
- Filtering, sorting, pagination is not supported
- Products are seeded into the database 
- Payments for cart items on orders is not integrated

## Requirements
To run the API, you must have:
- **PHP** (https://www.php.net/downloads)
- **MySQL** (https://dev.mysql.com/downloads/installer)

## Running the API

Create an `.env` file using the command. You can use this config or change it for your purposes. 

```console
$ cp .env.example .env
```

### Environment
Configure environment variables in `.env` for dev environment based on your MYSQL database configuration

```  
DB_CONNECTION=<YOUR_MYSQL_TYPE>
DB_HOST=<YOUR_MYSQL_HOST>
DB_PORT=<YOUR_MYSQL_PORT>
DB_DATABASE=<YOUR_DB_NAME>
DB_USERNAME=<YOUR_DB_USERNAME>
DB_PASSWORD=<YOUR_DB_PASSWORD>
```

# API documentation:
API End points and documentation can be found at:
[Postman Documentation](https://documenter.getpostman.com/view/5928045/SzYW2zck).

List of all API endpoints:

>POST /api/auth/login

>GET /api/auth/logout

>POST /api/auth/register

>POST /api/carts

>GET /api/carts/{id}/products

>POST /api/carts/{id}/products

>DELETE /api/carts/{id}/products/{id}

>PATCH /api/carts/{id}/products/{id}

>POST /api/orders

>GET /api/products

>GET /api/products/{id}

>GET /api/data

### Installation
Install the dependencies and start the server

```console
$ composer install
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan passport:install
$ php artisan serve
```

## Testing 
To run integration tests: 
```console
$ composer test
```

## Swagger
Generate swagger documentation

```console
$ php artisan l5-swagger:generate
```

([Link to Swagger Documentation](http://127.0.0.1:8000/api/documentation))
