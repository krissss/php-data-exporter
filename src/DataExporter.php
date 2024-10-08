<?php

namespace Kriss\DataExporter;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Kriss\DataExporter\DataExporter\Handler;
use Kriss\DataExporter\Writer\CsvSpoutWriter;
use Kriss\DataExporter\Writer\OdsSpoutWriter;
use Kriss\DataExporter\Writer\OdsSpreadsheetWriter;
use Kriss\DataExporter\Writer\XlsSpreadsheetWriter;
use Kriss\DataExporter\Writer\XlsxSpoutWriter;
use Kriss\DataExporter\Writer\XlsxSpreadsheetWriter;
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
 * @method static Handler csvSpreadsheet($source, array $options = [])
 * @method static Handler xlsSpreadsheet($source, array $options = [])
 * @method static Handler xlsxSpreadsheet($source, array $options = [])
 * @method static Handler odsSpreadsheet($source, array $options = [])
 */
class DataExporter
{
    protected static ?ContainerContract $container = null;

    public static function __callStatic($name, $arguments)
    {
        if (static::$container === null) {
            static::$container = static::getContainer();
        }

        return static::$container->make(Handler::class, [
            'container' => static::$container,
            'source' => $arguments[0],
            'writer' => $name,
            'writerOptions' => $arguments[1] ?? [],
        ]);
    }

    final protected static function getContainer(): ContainerContract
    {
        $container = static::getContainerInstance();

        if (! $container->has(Handler::CONTAINER_DATA_EXPORT_CONFIG_KEY)) {
            $container->singleton(Handler::CONTAINER_DATA_EXPORT_CONFIG_KEY, function () {
                return array_merge([
                    'writer' => static::writerConfig(),
                    'deleteFirstIfExist' => true,
                ], static::customConfig());
            });
        }

        return $container;
    }

    private static $setContainer = null;

    public static function setContainer(ContainerContract $container)
    {
        static::$setContainer = $container;
    }

    /**
     * @return ContainerContract
     */
    protected static function getContainerInstance(): ContainerContract
    {
        return static::$setContainer ?: new Container();
    }

    /**
     * @return array<string, array{class: string, options: array, extension: string}>
     */
    public static function writerConfig(): array
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
            'csvSpreadsheet' => [
                'class' => CsvSpoutWriter::class,
                'options' => [],
                'extension' => 'csv',
            ],
            'xlsSpreadsheet' => [
                'class' => XlsSpreadsheetWriter::class,
                'options' => [],
                'extension' => 'xls',
            ],
            'xlsxSpreadsheet' => [
                'class' => XlsxSpreadsheetWriter::class,
                'options' => [],
                'extension' => 'xlsx',
            ],
            'odsSpreadsheet' => [
                'class' => OdsSpreadsheetWriter::class,
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
