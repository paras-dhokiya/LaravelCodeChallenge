# Laravel Code Challage App
----------

## Installation

Clone the repository

    git clone git@github.com:paras-dhokiya/LaravelCodeChallenge.git

Switch to the repo folder

    cd LaravelCodeChallenge

Install all the dependencies using composer

    composer update

Reset ALL the Cache using this command 

    php artisan config:cache

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

## API Specification

The api documention can be accessed at [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| X-Requested-With 	| XMLHttpRequest   	|
| Optional 	| Authorization    	| Token {JWT}      	|

Refer the [api specification](#api-specification) for more info.

----------
 
# Authentication
 
This applications uses JSON Web Token (JWT) to handle authentication. The token is passed with each request using the `Authorization` header with `Token` scheme. The JWT authentication middleware handles the validation and authentication of the token. Please check the following sources to learn more about JWT.
 
- https://jwt.io/introduction/
- https://self-issued.info/docs/draft-ietf-oauth-json-web-token.html
