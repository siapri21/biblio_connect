&aq<?php

use App\Kernel;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (($_SERVER['APP_ENV'] ?? '') === 'test') {
    $testDb = dirname(__DIR__).'/var/data_test.db';
    if (is_file($testDb)) {
        unlink($testDb);
    }

    // debug=true : évite d’utiliser un cache Doctrine / conteneur obsolète sans is_verified sur user.
    $kernel = new Kernel('test', true);
    $kernel->boot();
    $container = $kernel->getContainer();
    $em = $container->get('doctrine')->getManager();
    $schemaTool = new SchemaTool($em);
    $metadata = $em->getMetadataFactory()->getAllMetadata();
    if ($metadata !== []) {
        $schemaTool->createSchema($metadata);
    }
    $kernel->shutdown();
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
