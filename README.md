# Orders Management

This application create a simple orders management app, which allows the user to:
1. Add, edit and delete orders
2. See a list of all orders
3. Filter orders from today, last 7 days and all time
4. Filter orders by user or product name

The data will be stored in MySQL database in these tables: order, product and user. You need to create appropriate structure.

Special business requirements:
- Discount of 20% must be applied to the total cost of any order when at least 3 items of "Pepsi Cola" are selected. (see the wireframe)
Requirements:
- You could implement whatever design you like as long as it has all of the described functionality and makes sense both from a UX and development standpoints
- PSR coding style
- Included relevant unit tests
##### - Every Add and Update Order check and update stock automatically to adjust the stock level remain.


### Environment
Laravel 5.4, PHP 7, MySql 5.6

### PSR 2 standard
By php-cs-fixer all cotrollers, models and routes files are PSR 2 standard.

### phpUnit tested

Have tested with ```Laravel\BrowserKitTesting\TestCase as BaseTestCase```

So kindly change the $baseUrl as per your virtual / real host name at test/TestCase.php file eg. public $baseUrl = 'http://ordersmanagement.dev';

Test with `./vendor/bin/phpunit`

### How to install
* Clone the repository to your virtual host folder
* Run: composer install/update
* Copy .env.example to .env then change the .env file as per your settings including database connection
* Also check the config/database.php for more details database related configuration
* Run: database migration command from root folder [eg. php artisan migrate:refresh --seed]
* there will be three users:
* john@test.com [Password: password]
* laura@test.com [Password: password]
* jon@test.com [Password: password]
* All documents including ER diagram, DFD inside public/document folder
* Primacy custom coding files are:
##### routes/web.php
##### controllers/OrdersController.php
##### controllers/HomeController.php
##### app/User.php
##### app/Product.php
##### app/Order.php
##### views/home/*
##### views/orders/*
##### views/layouts/*
##### database/migrations/*
##### database/seeds/*
##### tests/Unit/*

### How to run
Run your virtual host or real host from the browser eg. http://ordersmanagement.dev/ so it will show you the login screen. You can enter one of any user's login details to Orders Management.
You can get several help how to create virtual host for laravel application.

After login successful it will show you the Order creation form. Validation are there. Fill all the boxes and submit.

#### Future purposes
Though I have created login system for user but there is no registration process now.

Some of the places needed to used Eloquent raw query. But we can use the step by method calls for them.

Also need to add more test especially Mock tests.

We can make all process as REST JSON API easily. By trasfering to JSON request and response.





