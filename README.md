# About

A barebones PHP microservice that makes use of the Silex web framework, which can easily be deployed to Heroku.

Uses Doctrine ORM and symfony style console for database management.

This is really just a learning tool for me (and others), and intended to be used with
forthcoming Chop-Shopper front end.  

# Requirements

# Installation

Let composer manage dependencies

```sh
$ git clone git@github.com:rbtitotito/chop-recipes.git # or clone your own fork
$ # create a postgres database with credentials matching environment variable
$ # DATABASE_URL.  
$ # Example:  export DATABASE_URL=postgres://chop_shop:chopping@localhost:<port>
$ cd chop-recipes
$ composer install
$ -- first time only
$ console doctrine:database:create
$ console doctrine:schema:load
$ phpunit
```
## Heroku ready
```
$ heroku create
$ git push heroku master
$ heroku open
```

# API Documentation

- TODO

## Credit

Shamlessly Based on lyrixx excellent
- [Silex-Kitchen-Edition](http://lyrixx.github.com/Silex-Kitchen-Edition).

For more information about using PHP on Heroku, see these Dev Center articles:

- [PHP on Heroku](https://devcenter.heroku.com/categories/php)
