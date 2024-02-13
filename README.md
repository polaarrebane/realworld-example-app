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

php artisan make:data RegisterUser -N Http/RequestData -s RequestData

touch app/Exceptions/RenderValidationExceptionAsJson.php
#update App\Exceptions\Handler::class

php artisan migrate
./vendor/bin/pest --filter "RegisterUserTest"

#--- LoginUser
php artisan make:data LoginUser -N Http/RequestData -s RequestData
#update App/Http/Controllers/UserController add @login
#update routes/api.php
./vendor/bin/pest --filter "LoginUserTest"

#--- GetUser
php artisan make:provider JwtAuthServiceProvider#update config/app.php add
#update config/app.php add provider

php artisan make:middleware JwtAuthenticate
#update app/Http/Kernel.php add 'protected' alias

#update App/Http/Controllers/UserController add @get
#update routes/api.php
./vendor/bin/pest --filter "GetUserTest"

#--- UpdateUser
php artisan make:data UpdateUser -N Http/RequestData -s RequestData
#update App/Http/Controllers/UserController add @update
#update routes/api.php
./vendor/bin/pest --filter "UpdateUserTest"

./vendor/bin/pest --group "User"
```

### Article
```bash
composer require -W spatie/laravel-sluggable
php artisan make:model -m Article 
php artisan make:model -m Tag
php artisan make:model Comment
#update App/Models/User::class

php artisan make:migration CreateFollowTable
php artisan make:migration CreateArticleTagPivotTable
php artisan make:migration CreateFavoriteTable

php artisan make:factory ArticleFactory
php artisan make:factory TagFactory

php artisan pest:test Article/CreateArticleTest.php
php artisan pest:test Article/DeleteArticleTest.php
php artisan pest:test Article/FavoriteArticleTest.php
php artisan pest:test Article/GetAllArticlesTest.php
php artisan pest:test Article/GetArticleBySlugTest.php
php artisan pest:test Article/GetFeedTest.php
php artisan pest:test Article/UnfavoriteArticleTest.php
php artisan pest:test Article/UpdateArticleTest.php

#--- CreateArticle
php artisan make:data CreateArticle -N Http/RequestData -s RequestData
php artisan make:controller ArticleController
php artisan make:resource ArticleResource
#update routes/api.php

php artisan migrate
./vendor/bin/pest --filter "CreateArticleTest"

#--- DeleteArticle
php artisan make:policy ArticlePolicy
#update App/Http/Controllers/ArticleController add @destroy
touch app/Exceptions/RenderAuthenticationExceptionAsJson.php
#update App\Exceptions\Handler::class
#update routes/api.php
./vendor/bin/pest --filter "DeleteArticleTest"

#--- FavoriteArticle
#update App/Http/Controllers/ArticleController add @favorite
#update routes/api.php
./vendor/bin/pest --filter "FavoriteArticleTest"

#--- UnfavoriteArticle
#update App/Http/Controllers/ArticleController add @unfavorite
#update routes/api.php
./vendor/bin/pest --filter "UnfavoriteArticleTest"

#--- GetAllArticles
php artisan make:data GetAllArticles -N Http/RequestData -s RequestData
php artisan make:resource ArticleCollection
#update App/Http/Controllers/ArticleController add @index
#update routes/api.php
./vendor/bin/pest --filter "GetAllArticlesTest"

#--- GetArticleBySlug
#update App/Http/Controllers/ArticleController add @show
#update routes/api.php
./vendor/bin/pest --filter "GetArticleBySlugTest"

#--- GetFeed
php artisan make:data GetFeed -N Http/RequestData -s RequestData
#update App/Http/Controllers/ArticleController add @feed
#update routes/api.php
./vendor/bin/pest --filter "GetFeedTest"

#--- UpdateArticle
php artisan make:data UpdateArticle -N Http/RequestData -s RequestData
#update App/Http/Controllers/ArticleController add @update
#update routes/api.php
./vendor/bin/pest --filter "UpdateArticleTest"

./vendor/bin/pest --group "Article"
```
