<?php
/**
 * Created by PhpStorm.
 * User: sergei.nechaenko
 * Date: 17.01.2019
 * Time: 10:38
 */

namespace Nechaienko\MongodbLogging;

use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class LogToMongoDb
{
    protected const CONNECTION_INDEX = 'connection';
    protected const COLLECTION_INDEX = 'collection';
    protected const LEVEL_INDEX = 'level';
    protected const NAME_INDEX = 'name';
    protected const CUSTOM_MODEL_INDEX = 'custom_model';
    protected const ADDITIONAL_FIELDS_INDEX = 'additional_fields';

    protected const DEFAULT_CONFIG = [
        self::CONNECTION_INDEX => 'mongodb',
        self::COLLECTION_INDEX => 'logs',
        self::LEVEL_INDEX => 'info',
        self::NAME_INDEX => 'mongoDbLogger',
        self::CUSTOM_MODEL_INDEX => MongoDbModel::class,
        self::ADDITIONAL_FIELDS_INDEX => []
    ];

    /**
     * Create a custom Monolog instance.
     *
     * @param  array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $resultConfig = $this->prepareConfig($config);
        $handler = new LogToMongoDbHandler(
            $resultConfig[self::CONNECTION_INDEX],
            $resultConfig[self::COLLECTION_INDEX],
            $resultConfig[self::LEVEL_INDEX]
        );
        $handler->setMongoDbModel(new $resultConfig[self::CUSTOM_MODEL_INDEX]);
        $handler->setAdditionalFields($resultConfig[self::ADDITIONAL_FIELDS_INDEX]);

        $processor = new IntrospectionProcessor();

        $logger = new Logger(
            $resultConfig[self::NAME_INDEX],
            [
                $handler
            ],
            [
                $processor
            ]
        );

        return $logger;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function prepareConfig(array $config): array
    {
        $resultConfig = [];
        foreach (self::DEFAULT_CONFIG as $configKey => $configValue) {
            if (array_key_exists($configKey, $config)) {
                $resultConfig [$configKey] = $config[$configKey];
            } else {
                $resultConfig [$configKey] = $configValue;
            }
        }
        return $resultConfig;
    }
}
