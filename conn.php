<?php

define("AUTH_USERNAME", 'admin@example.com');
define("AUTH_PASSWORD", '123456');
$database_path='https://buiderform-default-rtdb.firebaseio.com';

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('buiderform-firebase-adminsdk-fdn10-12a1fd5cf5.json')->withDatabaseUri($database_path);

$database = $factory->createDatabase();

return  $database;
?>