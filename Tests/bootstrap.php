<?php

require_once __DIR__.'/../../../Tests/bootstrap.php';

$envFile = __DIR__.'/../../../../.env';
if (file_exists($envFile)) {
    (new Symfony\Component\Dotenv\Dotenv())
        ->usePutenv()
        ->bootEnv($envFile);
}
