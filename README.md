# About

A barebones PHP microservice that makes use of the Silex web framework, which can easily be deployed to Heroku.

Uses Doctrine ORM and symfony style console for database management.

This is really just a learning tool for me (and others), and intended to be used with
forthcoming Chop-Shopper front end.  

# Requirements

Currently running on PHP version 5.6 or greater.  Should be able to get it running on earlier versions of PHP with some modifications to dependencies.

Set database environment variable DATABASE_URL for db connection string to parse:
```sh
$ export DATABASE_URL=postgres://chop_shop:chopping@localhost:<port>
```

# Installation

Let composer manage dependencies

```sh
$ git clone git@github.com:rbtitotito/chop-recipes.git # or clone your own fork
$ # create a postgres database with credentials matching environment variable
$ # DATABASE_URL.  
$ # Example:  export DATABASE_URL=postgres://chop_shop:chopping@localhost:<port>
$
$ cd chop-recipes
$ composer install
$
$ # first time only - load database
$ console doctrine:database:create
$ console doctrine:schema:load
$ phpunit
```
## Heroku ready

Todo - add prepopulated database

```
$ heroku create
$ git push heroku master
$ heroku open
```

# API Documentation

Example Recipe:

```json
{
    "name": "Call my Doctor",
    "steps":
    [   
        {
            "directions": "Mix the eggs and the bacon",
            "step_ingredients":
            [
                {
                    "ingredient": {"name": "egg"},
                    "qty": 2
                },
                {
                    "ingredient": {"name": "bacon"},
                    "qty": 4
                }
            ]
        },
        {
            "directions": "Butter your biscuit",
            "step_ingredients":
            [
                {
                    "ingredient": {"name": "biscuit"},
                    "qty": 1
                },
                {
                    "ingredient": {"name": "butter"},
                    "qty": 1
                }
            ]
        }
    ]
}
```

- TODO

* Add Missing Http Verbs (Patch|Put|Head)
* Return Rel links for next actions
* Move Controller logic into Classes
* Fix Travis CI Builds (Mock Objects in Order)
* Build Ingredient list for entire Recipe based on Ingredient Steps
* Unit Tests
* MongoDB seems like a nicer choice for this dataset
* Modified by
* Add More details to ingredients and steps
* Tag Ingredients
* Add FrontEnd (MongoShopper)
* Authentication/Authoriztion (for grins)
* More Tests!

## Credit

Shamlessly Based on lyrixx excellent
- [Silex-Kitchen-Edition](http://lyrixx.github.com/Silex-Kitchen-Edition).

For more information about using PHP on Heroku, see these Dev Center articles:

- [PHP on Heroku](https://devcenter.heroku.com/categories/php)
