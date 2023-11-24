<?php
$params = require __DIR__ . '/configuration.php';
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.$params['dbhostname'].';dbname='.$params['dbname'],
    'username' => $params['dbusername'],
    'password' => $params['dbpassword'],
    'charset' => 'utf8',
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];