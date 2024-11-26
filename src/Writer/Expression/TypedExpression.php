<?php

namespace Kriss\DataExporter\Writer\Expression;

use PhpOffice\PhpSpreadsheet\Cell\DataType as SpreadsheetDataType;

class TypedExpression
{
    public const TYPE_NUMERIC = 0;
    public const TYPE_STRING = 1;
    public const TYPE_FORMULA = 2;
    public const TYPE_EMPTY = 3;
    public const TYPE_BOOLEAN = 4;
    public const TYPE_DATE = 5;
    public const TYPE_ERROR = 6;

    private $expression;
    private $type;

    public function __construct($expression, int $type)
    {
        $this->expression = $expression;
        $this->type = $type;
    }

    public function getRawValue()
    {
        return $this->expression;
    }

    public function getValue()
    {
        if ($this->type === self::TYPE_NUMERIC) {
            return (float)$this->expression;
        }
        if ($this->type === self::TYPE_BOOLEAN) {
            return (bool)$this->expression;
        }
        if ($this->type === self::TYPE_DATE) {
            if ($this->expression instanceof \DateTimeInterface) {
                return $this->expression->format(DATE_ATOM);
            }

            return $this->expression;
        }
        if ($this->type === self::TYPE_EMPTY) {
            return null;
        }

        return $this->expression;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getSpreadsheetType(): string
    {
        return [
            self::TYPE_NUMERIC => SpreadsheetDataType::TYPE_NUMERIC,
            self::TYPE_STRING => SpreadsheetDataType::TYPE_STRING,
            self::TYPE_FORMULA => SpreadsheetDataType::TYPE_FORMULA,
            self::TYPE_EMPTY => SpreadsheetDataType::TYPE_NULL,
            self::TYPE_BOOLEAN => SpreadsheetDataType::TYPE_BOOL,
            self::TYPE_DATE => SpreadsheetDataType::TYPE_ISO_DATE,
            self::TYPE_ERROR => SpreadsheetDataType::TYPE_ERROR,
        ][$this->type] ?? SpreadsheetDataType::TYPE_STRING;
    }

    public static function fromValue($value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_bool($value)) {
            return new self($value, self::TYPE_BOOLEAN);
        }
        if ($value === null || $value === '') {
            return new self($value, self::TYPE_EMPTY);
        }
        if (is_int($value) || is_float($value)) {
            return new self($value, self::TYPE_NUMERIC);
        }
        if (is_string($value) && isset($value[0]) && $value[0] === '=') {
            return new self($value, self::TYPE_FORMULA);
        }
        if ($value instanceof \DateTimeInterface) {
            return new self($value, self::TYPE_DATE);
        }

        return new self($value, self::TYPE_STRING);
    }
}
