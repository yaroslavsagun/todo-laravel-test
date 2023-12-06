# Todo in Laravel

## 1. Installation

This app uses default laravel sail package for easy deploy in containers using docker-compose

<br/>

#### Clone github repo

```
git clone https://github.com/yaroslavsagun/todo-laravel-test.git
```

#### Rename .env file and install dependencies

```
mv .env.example .env
composer install
```

#### Run docker-compose using sail

```
sail up -d
```

#### Generate Laravel key, migrate and seed DB

```
sail artisan key:generate
sail artisan migrate
sail artisan db:seed
```

## Usage

Now the app is available on `localhost:8000/api`

Documentation can be accessed via `localhost:8000/swagger`

Task management API is protected with api_token,
which every user can generate using `/api/generate-api-token` endpoint.
You have to put this token in header: `Authorization: Bearer <token>`

To generate token you are required to specify an email of a user.
List of users and their emails can be retrieved with `/api/get-users` endpoint.

Of course, this is only for test purposes, If it was a real project -
api_token would be generated using email and password encrypted with md5 or another method.

## Why sail

I've chosen laravel sail default package to quickly deploy app using in-build docker-compose.
Usually I use it only for local development, while prod/dev environment
is run using classic docker-compose command.

