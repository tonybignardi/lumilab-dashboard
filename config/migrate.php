<?php
/**
 * Migration configuration for Yii 2
 */

$config = [
    'id' => 'migrate-app',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => require __DIR__ . '/db.php',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@app/migrations',
        ],
    ],
];

return $config;
