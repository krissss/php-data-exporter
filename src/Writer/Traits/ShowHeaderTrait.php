<?php

namespace Kriss\DataExporter\Writer\Traits;

trait ShowHeaderTrait
{
    /**
     * @var null|bool null is auto
     */
    protected $showHeaders = null;

    /**
     * @param array $data
     * @return bool
     */
    public function shouldAddHeader(array $data): bool
    {
        if (is_bool($this->showHeaders)) {
            return $this->showHeaders;
        }
        $strKeys = array_filter(array_keys($data), function ($key) {
            return is_string($key);
        });

        return count($strKeys) > 0;
    }
}
