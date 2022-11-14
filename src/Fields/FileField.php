<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldFilePathException;
use Lkt\Factory\Schemas\Values\FieldFilePathValue;

class FileField extends AbstractField
{
    protected $storePath;
    protected $publicPath;


    /**
     * @param $path
     * @return $this
     * @throws InvalidFieldFilePathException
     */
    final public function setStorePath($path): self
    {
        $this->storePath = new FieldFilePathValue($path);
        return $this;
    }

    /**
     * @return string
     */
    final public function getStorePath(): string
    {
        if ($this->storePath instanceof FieldFilePathValue) {
            return $this->storePath->getValue();
        }
        return '';
    }


    /**
     * @param $path
     * @return $this
     * @throws InvalidFieldFilePathException
     */
    final public function setPublicPath($path): self
    {
        $this->publicPath = new FieldFilePathValue($path);
        return $this;
    }

    /**
     * @return string
     */
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