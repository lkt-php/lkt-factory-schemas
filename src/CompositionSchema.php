<?php

namespace Lkt\Factory\Schemas;

use Lkt\Factory\Schemas\Exceptions\SchemaNotDefinedException;
use Lkt\Factory\Schemas\Fields\RelatedField;
use Lkt\Factory\Schemas\ValueObjects\CompositionContent;

class CompositionSchema
{
    /** @var CompositionSchema[]  */
    private static array $stack = [];

    protected string $component = '';

    protected array $compositionContent = [];

    protected RelatedField $relatedField;
    protected string $relatedFieldName = '';

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
}