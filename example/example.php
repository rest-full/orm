<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/pathServer.php';

use App\AppModel;
use Restfull\Core\Aplication;

$app = new Aplication();
$app->bootstrapDatabase(
        'default',
        [
                'drive' => 'mysql',
                'username' => 'sws',
                'password' => 'SWS25!!89',
                'host' => 'localhost',
                'dbname' => 'miguelcoutoonl'
        ]
);
$table = new AppModel();
$exeute = $table->tableRegistry(
        ['main' => [['table' => 'usuarios']], 'join' => [['usuarios' => [['table' => []]]]]]
)->dataQuery(['fields' => ['usuarios']])->find('first')->getIterator()->itens();
print_r($exeute);