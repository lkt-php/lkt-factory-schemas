<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldFilePathException;
use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;
use Lkt\Factory\Schemas\Values\FieldFilePathValue;

class FileField extends AbstractField
{
    const TYPE = 'file';

    use FieldWithNullOptionTrait;

    protected ?FieldFilePathValue $storePath = null;
    protected ?FieldFilePathValue $publicPath = null;

    protected float|null $httpCacheDurationInSeconds = null;


    /**
     * @throws InvalidFieldFilePathException
     */
    final public function setStorePath(string|callable $path): self
    {
        $this->storePath = new FieldFilePathValue($path);
        return $this;
    }

    final public function getStorePath($instance = null): string
    {
        if ($this->storePath instanceof FieldFilePathValue) {
            return $this->storePath->getValue($instance);
        }
        return '';
    }


    /**
     * @throws InvalidFieldFilePathException
     */
    final public function setPublicPath(string|callable $path): self
    {
        $this->publicPath = new FieldFilePathValue($path);
        return $this;
    }

    final public function getPublicPath($instance = null): string
    {
        if ($this->publicPath instanceof FieldFilePathValue) {
            return $this->publicPath->getValue($instance);
        }
        return '';
    }

    /**
     * @return bool
     */
    final public function hasPublicPath($instance = null): bool
    {
        return $this->getPublicPath($instance) !== '';
    }

    final public function hasHttpCacheDurationInSeconds(): bool
    {
        return $this->httpCacheDurationInSeconds !== null;
    }

    final public function getHttpCacheDurationInSeconds(): float
    {
        return (float)$this->httpCacheDurationInSeconds;
    }

    final public function setHttpCacheDurationInSeconds(float $seconds): static
    {
        $this->httpCacheDurationInSeconds = $seconds;
        return $this;
    }

    final public function setHttpCacheDurationInSecondsToOneDay(): static
    {
        $this->httpCacheDurationInSeconds = 86400;
        return $this;
    }

    final public function setHttpCacheDurationInSecondsToOneWeek(): static
    {
        $this->httpCacheDurationInSeconds = 604800;
        return $this;
    }

    final public function setHttpCacheDurationInSecondsToOneMonth(): static
    {
        $this->httpCacheDurationInSeconds = 2419200;
        return $this;
    }

    final public function setHttpCacheDurationInSecondsToOneYear(): static
    {
        $this->httpCacheDurationInSeconds = 31536000;
        return $this;
    }
}