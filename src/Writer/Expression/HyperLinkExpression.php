<?php

namespace Kriss\DataExporter\Writer\Expression;

class HyperLinkExpression extends TypedExpression
{
    public function __construct(string $url, string $name = '')
    {
        $name = $name ?: $url;
        $url = str_replace('"', '""', $url);
        $name = str_replace('"', '""', $name);
        parent::__construct("=HYPERLINK(\"{$url}\", \"{$name}\")", self::TYPE_FORMULA);
    }
}
