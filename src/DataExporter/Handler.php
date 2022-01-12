<?php

namespace Kriss\DataExporter\DataExporter;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Kriss\DataExporter\Exceptions\FileAlreadyExistException;
use Sonata\Exporter\Writer\WriterInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Handler
{
    public const CONTAINER_DATA_EXPORT_CONFIG_KEY = 'kriss-data-export-config';

    protected $container;
    protected $writer;
    protected $writerOptions;
    protected $builder;

    public function __construct(Container $container, $source, string $writer, array $writerOptions = [])
    {
        $this->container = $container;
        $this->writer = $writer;
        $this->writerOptions = $writerOptions;
        $this->builder = (new Builder())
            ->withSource($source);
    }

    private $events = [];

    /**
     * @param array $events [$eventName => $handler]
     * @return $this
     * @see Builder::EVENT_
     */
    public function withEvents(array $events): self
    {
        $this->events = array_merge($this->events, $events);

        return $this;
    }

    private $filename;

    /**
     * 保存文件
     * @param string $filename 支持 __DIR__ . '/name', name 可以忽略后缀
     * @return string 保存后的文件路径
     */
    public function saveAs(string $filename): string
    {
        $this->filename = $filename = $this->makeFilename($filename);
        if (file_exists($filename)) {
            if ($this->getConfig()['deleteFirstIfExist']) {
                unlink($filename);
            } else {
                throw new FileAlreadyExistException($filename);
            }
        }
        $builder = $this->builder->withWriter($this->makeWriter($filename));
        foreach ($this->events as $eventName => $handler) {
            $builder->on($eventName, $handler);
        }
        $builder->export();

        return $filename;
    }

    /**
     * 浏览器下载
     * @param string $downloadName 下载时的名字，可以忽略后缀
     * @return StreamedResponse
     */
    public function browserDownload(string $downloadName): StreamedResponse
    {
        $response = new StreamedResponse(function () {
            $builder = $this->builder->withWriter($this->makeWriter('php://output'));
            foreach ($this->events as $eventName => $handler) {
                $builder->on($eventName, $handler);
            }
            $builder->export();
        });

        if ($downloadName) {
            $name = basename($this->makeFilename($downloadName));
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $name,
                Str::ascii($name)
            ));
        }

        return $response;
    }

    /**
     * 非正常结束时进行清理
     */
    public function clean(): void
    {
        // 关闭 writer
        if ($this->builder) {
            if ($writer = $this->builder->getWriter()) {
                $writer->close();
            }
        }
        // 清理文件
        if ($this->filename && is_file($this->filename)) {
            @unlink($this->filename);
        }
    }

    /**
     * 生成的文件名
     * @param string $filename
     * @return string
     */
    public function makeFilename(string $filename): string
    {
        if (strpos($filename, 'php://') !== false) {
            return $filename;
        }
        $extension = $this->getWriterConfig()['extension'];
        if (pathinfo($filename, PATHINFO_EXTENSION) !== $extension) {
            $filename .= '.' . $extension;
        }

        return $filename;
    }

    /**
     * @return array{writer: array, deleteFirstIfExist: bool}
     */
    private function getConfig(): array
    {
        return $this->container->get(static::CONTAINER_DATA_EXPORT_CONFIG_KEY);
    }

    /**
     * @return array{class: string, options: array, extension: string}
     */
    private function getWriterConfig(): array
    {
        $config = $this->getConfig()['writer'];
        if (! isset($config[$this->writer])) {
            throw new InvalidArgumentException("writer【{$this->writer}】 not config");
        }

        return $config[$this->writer];
    }

    /**
     * @param string $filename
     * @return WriterInterface
     */
    private function makeWriter(string $filename): WriterInterface
    {
        $config = $this->getWriterConfig();

        return $this->container->make($config['class'], array_merge(
            [
                'filename' => $filename,
            ],
            $config['options'] ?? [],
            $this->writerOptions ?? []
        ));
    }
}
