# E-site test case
[![forthebadge](https://forthebadge.com/images/badges/built-by-developers.svg)](https://forthebadge.com)[![forthebadge](https://forthebadge.com/images/badges/open-source.svg)](https://forthebadge.com)

## Reference documentation:
List of technologies and version used in this project
- [Symfony](https://symfony.com/doc/current/index.html)
- [Swagger - OpenApi](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html)
- [Swagger - PHP](https://zircote.github.io/swagger-php/guide/annotations.html)

## Installation
```bash
# Now install project packages
composer install
# Copy a copy of .env.example name it .env
cp .env.example .env

# Now setup the .env for the minimal requirement database
uncomment the following line: 
- DATABASE_URL="mysql://DB_USER:DB_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=8&charset=utf8mb4"
and fillin your database credentials

# Starting the application
symfony server:start

# Migrate the database
php bin/console doctrine:migrations:migrate

# Populate the datebase with seats
php bin/console doctrine:fixtures:load
```

## Usage
To use the project go to the [api documentation page](http://127.0.0.1:8000/api/doc) and make use of the solution endpoint.
