![PHP version](https://img.shields.io/badge/php-8.3-777bb3.svg?logo=php)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
![sponsored](https://pride-badges.pony.workers.dev/static/v1?label=Sponsored+by+the+Gay+Agenda&labelColor=%23555&stripeWidth=8&stripeColors=E40303%2CFF8C00%2CFFED00%2C008026%2C24408E%2C732982)

![pest](https://github.com/polaarrebane/realworld-example-app/actions/workflows/apidog.yml/badge.svg)
![pest](https://github.com/polaarrebane/realworld-example-app/actions/workflows/lint.yml/badge.svg)
![pest](https://github.com/polaarrebane/realworld-example-app/actions/workflows/pest.yml/badge.svg)


# RealWorld Example App - Laravel 10 Backend

This project represents the backend implementation for the [RealWorld](https://codebase.show/projects/realworld) using the Laravel 10 framework.

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

## Installation & Run
```bash
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

## Tests
```bash
./vendor/bin/pest
apidog run tests/Api/conduit.apidog-cli.json  -r cli
```

## Code Quality
```bash
./vendor/bin/pint
./vendor/bin/phpstan analyse --memory-limit=2G
```

## Dependencies
* [jwt-auth](https://jwt-auth.readthedocs.io/)
* [laravel-sluggable](https://github.com/spatie/laravel-sluggable)
* [laravel-data](https://spatie.be/docs/laravel-data)

## Development Steps

Below are the sequential steps followed during the project's development

<details>
  <summary>Details</summary>

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

### Profile
```bash
php artisan pest:test Profile/GetProfileTest.php
php artisan pest:test Profile/FollowProfileTest.php
php artisan pest:test Profile/UnfollowProfileTest.php

php artisan make:model Profile 

php artisan make:controller ProfileController
php artisan make:resource ProfileResource

#--- GetProfile
#update App/Http/Controllers/ArticleController add @show
#update routes/api.php
./vendor/bin/pest --filter "GetProfileTest"

#--- FollowProfile
#update App/Http/Controllers/ArticleController add @follow
#update routes/api.php
./vendor/bin/pest --filter "FollowProfileTest"

#--- UnfollowProfile
#update App/Http/Controllers/ArticleController add @unfollow
#update routes/api.php
./vendor/bin/pest --filter "UnfollowProfileTest"

./vendor/bin/pest --group "Profile"
```

### Comment
```bash
php artisan pest:test Comment/GetAllCommentsTest.php
php artisan pest:test Comment/CreateCommentTest.php
php artisan pest:test Comment/DeleteCommentTest.php

#update App/Models/Comment
php artisan make:migration CreateCommentsTable
php artisan migrate
php artisan make:factory CommentFactory
php artisan make:controller CommentController
php artisan make:resource CommentResource

#--- CreateComment
php artisan make:data CreateComment -N Http/RequestData -s RequestData
#update App/Http/Controllers/ArticleController add @store
#update routes/api.php
./vendor/bin/pest --filter "CreateCommentTest"

#--- DeleteComment
php artisan make:policy CommentPolicy
#update App/Http/Controllers/ArticleController add @destroy
#update routes/api.php
./vendor/bin/pest --filter "DeleteCommentTest"

#--- GetAllComments
php artisan make:resource CommentCollection
#update App/Http/Controllers/ArticleController add @index
#update routes/api.php
./vendor/bin/pest --filter "GetAllCommentsTest"

./vendor/bin/pest --group "Comment"
```

### Tag
```bash
php artisan pest:test Tag/GetAllTagsTest.php
php artisan make:controller TagController
#update routes/api.php
./vendor/bin/pest --filter "GetAllTagsTest"

./vendor/bin/pest --group "Tag"
```
</details>
