# Rest-full ORM

## About Rest-full ORM

Rest-full ORM is a small part of the Rest-Full framework.

You can find the application at: [rest-full/app](https://github.com/rest-full/app) and you can also see the framework skeleton at: [rest-full/rest-full](https://github.com/rest-full/rest-full).

## Installation

* Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
* Run `php composer.phar require rest-full/orm` or composer installed globally `compser require rest-full/orm` or composer.json `"rest-full/orm": "1.0.0"` and install or update.

## Usage

This ORM
```
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
```
## License

The rest-full framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).