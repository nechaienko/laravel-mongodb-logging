<?php
/**
 * Created by PhpStorm.
 * User: sergei.nechaenko
 * Date: 17.01.2019
 * Time: 10:48
 */

namespace Nechaienko\MongodbLogging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class LogToDbHandler
 *
 * @package danielme85\LaravelLogToDB
 */
class LogToMongoDbHandler extends AbstractProcessingHandler
{
    protected $connection;
    protected $collection;
    protected $mongoDbModel = MongoDbModel::class;
    protected $additionalFields = [];

    /**
     * LogToMongoDbHandler constructor.
     * @param $connection
     * @param $collection
     * @param MongoDbModel $mongoDbModel
     * @param int $level
     */
    function __construct($connection, $collection, $level = Logger::DEBUG, bool $bubble = true)
    {
        $this->connection = $connection;
        $this->collection = $collection;

        parent::__construct($level, $bubble);
    }

    /**
     * @param MongoDbModel $mongoDbModel
     */
    public function setMongoDbModel(MongoDbModel $mongoDbModel): void
    {
        $this->mongoDbModel = \get_class($mongoDbModel);
    }

    /**
     * @param MongoDbModel $mongoDbModel
     */
    public function setAdditionalFields(array $additionalFields): void
    {
        $this->additionalFields = $additionalFields;
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        if (!empty($record)) {
            try {
                $log = new $this->mongoDbModel($this->connection, $this->collection);
                $this->fill($log, $record);
                $this->fillAdditional($log);
                $log->save();
            } catch (\Exception $e) {
                //
            }
        }
    }

    /**
     * @param array $record
     */
    protected function fill(MongoDbModel $log, array $record): void
    {
        foreach ($log::FIELDS_TO_SET as $fieldKey) {
            if (isset($record[$fieldKey])) {
                $log->{$fieldKey} = $record[$fieldKey];
            }
        }
    }

    /**
     * @param array $record
     */
    protected function fillAdditional(MongoDbModel $log): void
    {
        foreach ($this->additionalFields as $fieldKey => $fieldValue) {
            $log->{$fieldKey} = $fieldValue;
        }
    }
}
