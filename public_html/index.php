<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../helpers/helpers.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV'));

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require __DIR__ . '/../config/web.php';

$application = new yii\web\Application($config);
$application->run();

