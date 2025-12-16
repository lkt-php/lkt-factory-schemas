<?php

namespace Lkt\Factory\Schemas;

use Lkt\Factory\Schemas\Exceptions\SchemaNotDefinedException;
use Lkt\Factory\Schemas\Fields\AbstractField;
use Lkt\Factory\Schemas\Fields\RelatedField;
use Lkt\Factory\Schemas\ValueObjects\CompositionContent;

/**
 *
 * @deprecated
 */
class CompositionSchema
{
    /** @var CompositionSchema[]  */
    private static array $stack = [];

    protected string $component = '';

    protected array $compositionContent = [];

    protected array $compositionValues = [];

    protected RelatedField $relatedField;
    protected string $relatedFieldName = '';

    /**
     * @param string $paramName
     * @param string $extractParamValueFromFieldName
     * @return $this
     * @deprecated
     */
    public function setCompositionValue(string $paramName, string $extractParamValueFromFieldName): static
    {
        $this->compositionValues[$paramName] = $extractParamValueFromFieldName;
        return $this;
    }

    /**
     * @param string $paramName
     * @return mixed
     * @deprecated
     */
    public function getCompositionValue(string $paramName): mixed
    {
        return $this->compositionValues[$paramName];
    }

    public function getCompositionValueFields(): array
    {
        $schema = Schema::get($this->component);
        $r = [];
        foreach ($this->compositionValues as $paramName => $compositionValue) {
            $r[$paramName] = $schema->getField($compositionValue);
        }

        return $r;
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    public static function define(string $component): static
    {
        return new static($component);
    }

    public static function defineGlobally(string $component): static
    {
        $r = new static($component);
        static::$stack[$component] = $r;
        return $r;
    }

    public static function register(CompositionSchema $schema): void
    {
        $code = $schema->getComponent();
        static::$stack[$code] = $schema;
    }

    /**
     * @throws SchemaNotDefinedException
     */
    public static function get(string $code): ?self
    {
        if (!static::$stack[$code] instanceof CompositionSchema) {
            return null;
        }
        return static::$stack[$code];
    }

    public function __construct(string $component)
    {
        $this->component = $component;
    }

    public function setCompositionContent(RelatedField|string $relatedField, array $fields): static
    {
        $content = new CompositionContent($this->component, $relatedField, $fields);
        $this->compositionContent[$content->getFieldName()] = $content;
        return $this;
    }

    public function getCompositionContent(string $relatedField): ?CompositionContent
    {
        return $this->compositionContent[$relatedField];
    }

    /**
     * @return CompositionContent[]
     */
    public function getAllCompositionContent(): array
    {
        return $this->compositionContent;
    }

    public function hasField(string $fieldName): bool
    {
        $included = false;

        foreach ($this->compositionContent as $compositionContent) {
            if (in_array($fieldName, $compositionContent->fields)) {
                $included = true;
                break;
            }
        }

        return $included;
    }

    public function getField(string $fieldName): ?AbstractField
    {
        $r = null;

        foreach ($this->compositionContent as $compositionContent) {
            if (in_array($fieldName, $compositionContent->fields)) {
                $schema = Schema::get($compositionContent->getRelatedField()->getComponent());
                $r = $schema->getField($fieldName);
                break;
            }
        }

        return $r;
    }

    public function getRelatedFieldHandlingThisField(string $fieldName): ?AbstractField
    {
        $r = null;

        foreach ($this->compositionContent as $compositionContent) {
            if (in_array($fieldName, $compositionContent->fields)) {
                $r = $compositionContent->getRelatedField();
                break;
            }
        }

        return $r;
    }

    public function getComposedFields(): array
    {
        $r = [];

        foreach ($this->compositionContent as $compositionContent) {
            $schema = Schema::get($compositionContent->getRelatedField()->getComponent());
            foreach ($compositionContent->fields as $fieldName => $field) {
                $r[$fieldName] = $schema?->getField($field);
            }
        }

        return $r;
    }
}