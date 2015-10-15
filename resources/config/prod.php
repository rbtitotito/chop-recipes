<?php

    $app[ 'locale' ]                 = 'en';
    $app[ 'session.default_locale' ] = $app[ 'locale' ];

    // Cache
    $app[ 'cache.path' ] = __DIR__ . '/../cache';

    // Http cache
    $app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

    // database config (local)
    $db_config = (file_exists('database.php')) ? include 'database.php' : array();

    /**
     * This function reads database_url from environment and parses correctly
     * for database connect string (heroku setup)
     * @todo move this to new file
     **/
    function parse_db_url()
    {
        global $db_config;
        $ret = array();
        // prefer database_url from env var
        if (getenv("DATABASE_URL")) {
            $parsed_url = parse_url(getenv("DATABASE_URL"));
        } else {
            $parsed_url = $db_config;
        }
        $ret[ 'dbname' ] = ( isset( $parsed_url[ 'path' ] ) ) ? substr($parsed_url[ 'path' ], 1) : 'chop_shop';

        if (isset( $parsed_url[ 'pass' ] )) {
            $ret[ 'password' ] = $parsed_url[ 'pass' ];
        }

        if (isset( $parsed_url[ 'password' ] )) {
            $ret[ 'password' ] = $parsed_url[ 'password' ];
        }

        if (isset( $parsed_url[ 'user' ] )) {
            $ret[ 'user' ] = $parsed_url[ 'user' ];
        }

        if (isset( $parsed_url[ 'host' ] )) {
            $ret[ 'host' ] = $parsed_url[ 'host' ];
        }

        return $ret;
    }

    // Doctrine (db)
    $db_options             = parse_db_url();
    $db_options[ 'driver' ] = 'pdo_pgsql';
    $app[ 'db.options' ]    = $db_options;
