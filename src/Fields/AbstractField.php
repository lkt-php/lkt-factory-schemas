<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\ColumnTypes\Enums\ColumnType;
use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Values\FieldColumnValue;
use Lkt\Factory\Schemas\Values\FieldNameValue;

abstract class AbstractField
{
    protected $name;
    protected $column;

    /**
     * @param string $name
     * @param string $column
     * @throws InvalidFieldNameException
     */
    public function __construct(string $name, string $column = '')
    {
        $this->name = new FieldNameValue($name);
        $this->column = new FieldColumnValue($column, $this->name->getValue());
    }

    final public function getName(): string
    {
        return $this->name->getValue();
    }

    final public function getColumn(): string
    {
        return $this->column->getValue();
    }

    /**
     * @param string $name
     * @param string $column
     * @return static
     * @throws InvalidFieldNameException
     */
    final public static function define(string $name, string $column = ''): self
    {
        return new static($name, $column);
    }

    public function getValidations()
    {
        // @todo
        return [];
    }

    public function getParser()
    {
        // @todo
        return null;
    }

    public function getType(): int
    {
        $type = -1;

        if ($this instanceof IntegerField) {
            $type = ColumnType::Integer;
        }

        if ($this instanceof FloatField) {
            $type = ColumnType::Float;
        }

        if ($this instanceof StringField) {
            $type = ColumnType::String;
        }

        if ($this instanceof HTMLField) {
            $type = ColumnType::HTML;
        }

        if ($this instanceof BooleanField) {
            $type = ColumnType::Boolean;
        }

        if ($this instanceof EmailField) {
            $type = ColumnType::Email;
        }

        if ($this instanceof ForeignKeyField) {
            $type = ColumnType::ForeignKey;
        }

        if ($this instanceof ForeignKeysField) {
            $type = ColumnType::ForeignKeys;
        }

        if ($this instanceof DateTimeField) {
            $type = ColumnType::Datetime;
        }

        if ($this instanceof UnixTimeStampField) {
            $type = ColumnType::UnixTimeStamp;
        }

        if ($this instanceof ImageField) {
            $type = ColumnType::Image;
        }

        if ($this instanceof FileField) {
            $type = ColumnType::File;
        }

        if ($this instanceof JSONField) {
            $type = ColumnType::JSON;
        }

        if ($this instanceof PivotField) {
            $type = ColumnType::Pivot;
        }

        if ($this instanceof RelatedField) {
            $type = ColumnType::Related;
        }

        if ($this instanceof RelatedKeysField) {
            $type = ColumnType::RelatedKeys;
        }

        if ($this instanceof ColorField) {
            $type = ColumnType::Color;
        }
        return $type;
    }
}