<?php

namespace Lkt\Factory\Schemas\CRUDs;

abstract class AbstractCRUD
{
    protected $className = '';

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public static function define(string $className): self
    {
        return new static($className);
    }

    /**
     * @return string
     */
    public function getAppClass(): string
    {
        return $this->className;
    }
}