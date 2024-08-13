<?php

namespace Kriss\DataExporter\DataExporter;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Kriss\DataExporter\Exceptions\FileAlreadyExistException;
use Sonata\Exporter\Writer\WriterInterface;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Handler
{
    public const CONTAINER_DATA_EXPORT_CONFIG_KEY = 'kriss-data-export-config';

    protected ContainerContract $container;
    protected string $writer;
    protected array $writerOptions;
    protected Builder $builder;

    public function __construct(ContainerContract $container, $source, string $writer, array $writerOptions = [])
    {
        $this->container = $container;
        $this->writer = $writer;
        $this->writerOptions = $writerOptions;
        $this->builder = (new Builder())
            ->withSource($source);
    }

    private array $events = [];

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

    private string $filename;

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
        $this->doBuilderExport($filename);

        return $filename;
    }

    /**
     * 浏览器下载
     * @param string $downloadName 下载时的名字，可以忽略后缀
     * @return StreamedResponse
     */
    public function browserDownload(string $downloadName): StreamedResponse
    {
        if (! class_exists(StreamedResponse::class)) {
            throw new InvalidArgumentException('must install symfony/http-foundation first');
        }

        $response = new StreamedResponse(function () {
            $this->doBuilderExport('php://output');
        });

        if ($downloadName) {
            $name = $this->makeFilename($downloadName, false);
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $name,
                str_replace('%', $this->getConfig()['filenameInvalidCharsReplace'], Str::ascii($name))
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
        if ($writer = $this->builder->getWriter()) {
            $writer->close();
        }
        // 清理文件
        if ($this->filename && is_file($this->filename)) {
            unlink($this->filename);
        }
    }

    /**
     * 生成的文件名
     * @param string $filename
     * @param bool $maybeHasPath filename 中是否可能包含路径
     * @return string
     */
    public function makeFilename(string $filename, bool $maybeHasPath = true): string
    {
        if (str_contains($filename, 'php://')) {
            return $filename;
        }

        $dirname = '';
        $fileBasename = $filename;
        if ($maybeHasPath) {
            // 格式化文件路径
            $filename = Path::canonicalize($filename);
            // 截取 / 之后的作为文件名
            if (($pos = strrpos($filename, '/')) !== false) {
                $dirname = substr($filename, 0, $pos + 1); // 保留 /
                $fileBasename = substr($filename, $pos + 1);
            }
        }

        $configExtension = $this->getWriterConfig()['extension'];
        if (($pos = strpos($fileBasename, '.' . $configExtension . '?')) !== false) {
            // 兼容同扩展名情况下，存在 ?x=1 的情况，比如：abc.csv?x=1，此时直接将 ? 后的删除
            $fileBasename = substr($fileBasename, 0, $pos);
        }

        // 去除特殊符号
        $invalidChars = ['/', '\\', ':', '*', '?', '"', '<', '>', '|'];
        $fileBasename = str_replace($invalidChars, array_fill(0, count($invalidChars) - 1, $this->getConfig()['filenameInvalidCharsReplace']), $fileBasename); // 替换文件系统不支持的特殊符号

        // 补全 ext
        if (pathinfo($fileBasename, PATHINFO_EXTENSION) !== $configExtension) {
            $fileBasename .= '.' . $configExtension;
        }

        return $dirname . $fileBasename;
    }

    /**
     * @return array{writer: array, deleteFirstIfExist: bool, filenameInvalidCharsReplace: string, filenameMaker: ?callable}
     */
    private function getConfig(): array
    {
        return array_merge([
            'writer' => [],
            'deleteFirstIfExist' => true,
            'filenameInvalidCharsReplace' => '_',
        ], $this->container->get(static::CONTAINER_DATA_EXPORT_CONFIG_KEY));
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
                'filename' => $filename, // 第一个参数必须
            ],
            $config['options'] ?? [], // 全局的配置
            $this->writerOptions ?? [] // 用户调用时的配置
        ));
    }

    private function doBuilderExport(string $filename): void
    {
        $builder = $this->builder->withWriter($this->makeWriter($filename));
        foreach ($this->events as $eventName => $handler) {
            $builder->on($eventName, $handler);
        }
        $builder->export();
    }
}
