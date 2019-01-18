## About Laravel-mongodb-logging

This package gives opportunity **logging to mongodb** with custom ***Laravel logging*** 

### Installation
```
composer require nechaienko/laravel-mongodb-logging
```
#### Usage
**config\logging.php**
```
'mogodb-channel' => [
            'driver' => 'custom',
            'via' => \Nechaienko\MongodbLogging\LogToMongoDb::class,
            'level' => 'info',
            'connection' => 'mongodb',
            'collection' => 'logs',         
        ],
``` 