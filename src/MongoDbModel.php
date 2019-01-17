<?php
/**
 * Created by PhpStorm.
 * User: sergei.nechaenko
 * Date: 17.01.2019
 * Time: 11:24
 */

namespace Nechaienko\MongodbLogging;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MongoDbModel extends Eloquent
{
    public $timestamps = false;

    protected $connection;
    protected $collection;

    public const FIELDS_TO_SET = [
        'message',
        'level',
        'level_name',
        'channel',
        'datetime',
        'extra',
        'detailed',
    ];

    function __construct($connection = 'mongodb', $collection = 'log')
    {
        $this->connection = $connection;
        $this->collection = $collection;
    }
}
