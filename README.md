# RealWorld Example App - Laravel 10 Backend

This project represents the backend implementation for the RealWorld application using the Laravel 10 framework.

## Functionality

This application implements the core functionality of RealWorld:

- User registration and authentication
- Displaying user profiles
- Creating, reading, updating, and deleting articles
- Adding and deleting comments to articles
- Ability to follow other users and view their articles

## Testing and Quality Assurance

- This project uses Pest as the testing framework and ApiDog for API testing.
- Larastan is used for static analysis.
- Pint is the chosen linter.

## Development Steps

Below are the sequential steps followed during the project's development

### Initial
```bash
composer create-project laravel/laravel .
composer require --dev larastan/larastan
touch phpstan.neon
touch pint.json
composer require --dev -W pestphp/pest pestphp/pest-plugin-faker pestphp/pest-plugin-laravel
./vendor/bin/pest --init
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
mkdir -p .github/workflows
touch .github/workflows/pest.yml
touch .github/workflows/apidog.yml
touch .github/workflows/lint.yml
```

### User
```bash
touch tests/Utils.php
php artisan pest:test User/RegisterUserTest.php
php artisan pest:test User/LoginUserTest.php
php artisan pest:test User/GetUserTest.php
php artisan pest:test User/UpdateUserTest.php

#update config/auth.php - Tymon JWT Auth
php artisan jwt:secret
          
#update App/Models/User::class
#update Database/Factories/UserFactory::class
php artisan make:migration AlterUsersTable
php artisan make:controller UserController
php artisan make:resource UserResource
#update routes/api.php

composer require spatie/laravel-data

php artisan make:data RegisterUser -N RequestData -s RequestData

touch app/Exceptions/RenderValidationExceptionAsJson.php
#update App\Exceptions\Handler::class

php artisan migrate:fresh
./vendor/bin/pest --filter "RegisterUserTest"
```
