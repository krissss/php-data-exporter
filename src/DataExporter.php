<?php

namespace Kriss\DataExporter;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Kriss\DataExporter\DataExporter\Handler;
use Kriss\DataExporter\Writer\CsvSpoutWriter;
use Kriss\DataExporter\Writer\OdsSpoutWriter;
use Kriss\DataExporter\Writer\XlsxSpoutWriter;
use Sonata\Exporter\Writer\CsvWriter;
use Sonata\Exporter\Writer\XlsWriter;
use Sonata\Exporter\Writer\XlsxWriter;

/**
 * method name is static::writerConfig() key
 * @see static::writerConfig()
 *
 * @method static Handler csv($source, array $options = [])
 * @method static Handler xls($source, array $options = [])
 * @method static Handler xlsx($source, array $options = [])
 * @method static Handler csvSpout($source, array $options = [])
 * @method static Handler xlsxSpout($source, array $options = [])
 * @method static Handler odsSpout($source, array $options = [])
 */
class DataExporter
{
    protected static $container;

    public static function __callStatic($name, $arguments)
    {
        if (!static::$container instanceof ContainerContract) {
            static::$container = static::getContainer();
        }

        return new Handler(static::$container, $arguments[0], $name, $arguments[1] ?? []);
    }

    /**
     * @return ContainerContract
     */
    final protected static function getContainer(): ContainerContract
    {
        $container = static::getContainerInstance();

        $container->singleton(Handler::CONTAINER_DATA_EXPORT_CONFIG_KEY, function () {
            return array_merge([
                'writer' => static::writerConfig(),
                'deleteFirstIfExist' => true,
            ], static::customConfig());
        });

        return $container;
    }

    /**
     * @return ContainerContract
     */
    protected static function getContainerInstance(): ContainerContract
    {
        return new Container();
    }

    /**
     * @return array[]
     */
    protected static function writerConfig(): array
    {
        return [
            'csv' => [
                'class' => CsvWriter::class,
                'options' => [
                    'withBom' => true,
                ],
                'extension' => 'csv',
            ],
            'xls' => [
                'class' => XlsWriter::class,
                'options' => [],
                'extension' => 'xls',
            ],
            'xlsx' => [
                'class' => XlsxWriter::class,
                'options' => [
                    'showFilters' => false,
                ],
                'extension' => 'xlsx',
            ],
            'csvSpout' => [
                'class' => CsvSpoutWriter::class,
                'options' => [],
                'extension' => 'csv',
            ],
            'xlsxSpout' => [
                'class' => XlsxSpoutWriter::class,
                'options' => [],
                'extension' => 'xlsx',
            ],
            'odsSpout' => [
                'class' => OdsSpoutWriter::class,
                'options' => [],
                'extension' => 'ods',
            ],
        ];
    }

    /**
     * 自定义的配置
     * @return array
     */
    protected static function customConfig(): array
    {
        return [];
    }
}
