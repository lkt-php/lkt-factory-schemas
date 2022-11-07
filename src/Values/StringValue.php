<?php

namespace Lkt\Factory\Schemas\Values;

final class StringValue
{
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value = '')
    {
        if (!$value) {
            $value = '';
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}