<?php
/**
  * Shamlessly Based on lyrixx excellent
  * @link[Silex-Kitchen-Edition](http://lyrixx.github.com/Silex-Kitchen-Edition).
  */
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

$console = new Application('Chop-Shopper', '0.1');

$app->boot();

//add Doctine ORM support
$em = $app['orm.em'];

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

$console->setHelperSet($helperSet);

$console->addCommands(array(
    // DBAL Commands
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),

));

if (isset($app['cache.path'])) {
    $console
        ->register('cache:clear')
        ->setDescription('Clears the cache')
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

            $cacheDir = $app['cache.path'];
            $finder = Finder::create()->in($cacheDir)->notName('.gitkeep');

            $filesystem = new Filesystem();
            $filesystem->remove($finder);

            $output->writeln(sprintf("%s <info>success</info>", 'cache:clear'));
        });
}

$console
    ->register('doctrine:schema:show')
    ->setDescription('Output schema declaration')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $schema = require __DIR__.'/../resources/db/schema.php';

        foreach ($schema->toSql($app['db']->getDatabasePlatform()) as $sql) {
            $output->writeln($sql.';');
        }
    })
;

$console
    ->register('doctrine:schema:load')
    ->setDescription('Load schema')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $schema = require __DIR__.'/../resources/db/schema.php';

        foreach ($schema->toSql($app['db']->getDatabasePlatform()) as $sql) {
            $app['db']->exec($sql.';');
        }
    })
;

$console
    ->register('doctrine:database:drop')
    ->setName('doctrine:database:drop')
    ->setDescription('Drops the configured databases')
    ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
    ->addOption('force', null, InputOption::VALUE_NONE, 'Set this parameter to execute this action')
    ->setHelp(
        <<<EOT
The <info>doctrine:database:drop</info> command drops the default connections
database:

<info>php app/console doctrine:database:drop</info>

The --force parameter has to be used to actually drop the database.

You can also optionally specify the name of a connection to drop the database
for:

<info>php app/console doctrine:database:drop --connection=default</info>

<error>Be careful: All data in a given database will be lost when executing
this command.</error>
EOT
    )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $connection = $app['db'];

        $params = $connection->getParams();

        $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);

        if (!$name) {
            throw new \InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
        }

        if ($input->getOption('force')) {
            // Only quote if we don't have a path
            if (!isset($params['path'])) {
                $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
            }

            try {
                $connection->getSchemaManager()->dropDatabase($name);
                $output->writeln(sprintf('<info>Dropped database for connection named <comment>%s</comment></info>', $name));
            } catch (\Exception $e) {
                $output->writeln(sprintf('<error>Could not drop database for connection named <comment>%s</comment></error>', $name));
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

                return 1;
            }
        } else {
            $output->writeln('<error>ATTENTION:</error> This operation should not be executed in a production environment.');
            $output->writeln('');
            $output->writeln(sprintf('<info>Would drop the database named <comment>%s</comment>.</info>', $name));
            $output->writeln('Please run the operation with --force to execute');
            $output->writeln('<error>All data will be lost!</error>');

            return 2;
        }
    })
;

$console
    ->register('doctrine:database:create')
    ->setDescription('Creates the configured databases')
    ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
    ->setHelp(
        <<<EOT
The <info>doctrine:database:create</info> command creates the default
connections database:

<info>php app/console doctrine:database:create</info>

You can also optionally specify the name of a connection to create the
database for:

<info>php app/console doctrine:database:create --connection=default</info>
EOT
    )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $connection = $app['db'];

        $params = $connection->getParams();
        $name = isset($params['path']) ? $params['path'] : $params['dbname'];

        unset($params['dbname']);

        $tmpConnection = DriverManager::getConnection($params);

        // Only quote if we don't have a path
        if (!isset($params['path'])) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $error = false;
        try {
            $tmpConnection->getSchemaManager()->createDatabase($name);
            $output->writeln(sprintf('<info>Created database for connection named <comment>%s</comment></info>', $name));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Could not create database for connection named <comment>%s</comment></error>', $name));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $error = true;
        }

        $tmpConnection->close();

        return $error ? 1 : 0;
    })
;

return $console;
