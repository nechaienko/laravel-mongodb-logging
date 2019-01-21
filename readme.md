## About Laravel-mongodb-logging

This package gives opportunity **logging to mongodb** with custom
***[Laravel logging](https://laravel.com/docs/5.7/logging)***

Package [jenssegers/laravel-mongodb](https://github.com/jenssegers/laravel-mongodb) is required

Installation
-----------------------------------
```
composer require nechaienko/laravel-mongodb-logging
```
Configuration
-----------------------------------
Add configurations to file `..\your_project\config\logging.php`
```
'mogodb-channel' => [
            'driver' => 'custom',
            'via' => \Nechaienko\MongodbLogging\LogToMongoDb::class,
            'level' => 'info',
            'connection' => 'mongodb',
            'collection' => 'logs',         
        ],
``` 

Customization
-----------------------------------
#### Additional fields

```
'mogodb-channel' => [
            ...
            'additional_fields' => [
                'environment' => config('app.env'),
                ...
            ],         
        ],
``` 

#### Fields formatting
You can override model from package and change fields format with
***[Laravel mutators](https://laravel.com/docs/5.7/eloquent-mutators)***

1. Create your model and override needed method  
```
namespace App\Services\Logging;

use Nechaienko\MongodbLogging\MongoDbModel as ParentMongoDbModel;

class MongoDbModel extends ParentMongoDbModel
{
    public function setDatetimeAttribute($value)
    {
        ...
        $this->attributes['datetime'] = $resultValue;
    }
}
``` 

1. Add your model to confs

```
'mogodb-channel' => [
            ...
            'custom_model' => \App\Services\Logging\MongoDbModel::class,         
        ],
``` 

#### Set default fields

You can define fields to set by overriding constant **FIELDS_TO_SET**

```
namespace App\Services\Logging;

use Nechaienko\MongodbLogging\MongoDbModel as ParentMongoDbModel;

class MongoDbModel extends ParentMongoDbModel
{
    public const FIELDS_TO_SET = [
        'message',
        'level_name',
        'datetime',
        'extra',
    ];
}
``` 

Usage
-----------------------------------

```
use Illuminate\Support\Facades\Log;
...
Log::channel('mogodb-channel')->info('message');
``` 