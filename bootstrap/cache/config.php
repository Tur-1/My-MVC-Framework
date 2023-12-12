<?php return array (
  'app' => 
  array (
    'name' => 'TurFramework',
    'env' => 'local',
    'debug' => 'true',
    'url' => 'http://mvc.test',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'key' => NULL,
    'cipher' => 'AES-256-CBC',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'mvc',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
      ),
    ),
    'migrations' => 'migrations',
  ),
);