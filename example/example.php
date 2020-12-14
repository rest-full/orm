<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/../config/pathServer.php';

use App\AppModel;
use Restfull\Core\Aplication;
use Restfull\ORM\Query;

$app = new Aplication();
$app->startsORM(
  [
    'default' => [
      'drive' => 'mysql',
      'username' => 'sws',
      'password' => 'SWS25!!89',
      'host' => 'localhost',
      'dbname' => 'miguelcoutoonl'
    ]
  ]
);
if ($app->executeNameDatabase('default')) {
    $table = new AppModel();
    $exeute = $table->executeQuery($table->tableRegistry(new Query([]),['main'=>'usuarios']),'first');
    print_r($exeute);
}

