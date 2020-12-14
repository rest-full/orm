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
require_once __DIR__.'/../config/pathServer.php';

use App\AppModel;
use Restfull\Core\Aplication;
use Restfull\ORM\Query;

$app = new Aplication();
$app->startsORM(
  [
    'default' => [
      'drive' => 'mysql',
      'username' => 'your user',
      'password' => 'your pass',
      'host' => 'your host',
      'dbname' => 'your database'
    ]
  ]
);
if ($app->executeNameDatabase('default')) {
    $table = new AppModel();
    $exeute = $table->executeQuery($table->tableRegistry(new Query([]),['main'=>'usuarios']),'first');
    print_r($exeute);
}
```
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).