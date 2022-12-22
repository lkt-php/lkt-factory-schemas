<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldFilePathException;
use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;
use Lkt\Factory\Schemas\Values\FieldFilePathValue;

class FileField extends AbstractField
{
    use FieldWithNullOptionTrait;

    protected ?FieldFilePathValue $storePath = null;
    protected ?FieldFilePathValue $publicPath = null;


    /**
     * @throws InvalidFieldFilePathException
     */
    final public function setStorePath(string $path): self
    {
        $this->storePath = new FieldFilePathValue($path);
        return $this;
    }

    final public function getStorePath(): string
    {
        if ($this->storePath instanceof FieldFilePathValue) {
            return $this->storePath->getValue();
        }
        return '';
    }


    /**
     * @throws InvalidFieldFilePathException
     */
    final public function setPublicPath(string $path): self
    {
        $this->publicPath = new FieldFilePathValue($path);
        return $this;
    }

    final public function getPublicPath(): string
    {
        if ($this->publicPath instanceof FieldFilePathValue) {
            return $this->publicPath->getValue();
        }
        return '';
    }

    /**
     * @return bool
     */
    final public function hasPublicPath(): bool
    {
        return $this->getPublicPath() !== '';
    }
}