<?php
    /**
     * Register Service Providers
     */
    use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
    use Kumatch\Silex\JsonBodyProvider;
    use Silex\Provider\HttpCacheServiceProvider;
    use Silex\Provider\MonologServiceProvider;

    $app->register(new HttpCacheServiceProvider());

    // Application log file
    $app->register(new MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__ . '/../resources/log/app.log',
        'monolog.name'    => 'app',
        'monolog.level'   => 100 // = Logger::DEBUG
    ));

    // get json properly from body
    $app->register(new JsonBodyProvider());

    $app[ 'monolog' ]->addDebug("db_options: " . var_export($db_options, true));
    // this picks up $app['db.options'] from config
    $app->register(new Silex\Provider\DoctrineServiceProvider());
    // Register Doctrine ORM
    $app->register(new DoctrineORMServiceProvider(), array(
        'orm.proxies_dir'           => __DIR__ . '/../resources/cache/doctrine/Proxy',
        'orm.proxies_namespace'     => 'DoctrineProxy',
        'orm.auto_generate_proxies' => true,
        'orm.em.options'            => array(
            'mappings' => array(
                array(
                    'type'      => 'annotation',
                    'namespace' => 'ChopShopper\Entity',
                    'path'      => __DIR__ . '/ChopShopper/Entity'
                )
            )
        )
    ));

    return $app;
