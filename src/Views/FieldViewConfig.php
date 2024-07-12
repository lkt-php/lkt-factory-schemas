<?php

namespace Lkt\Factory\Schemas\Views;

class FieldViewConfig
{
    protected string $name = '';
    protected string $mode = ''; //read | edit | data | hide
    protected string $type = '';
    protected array $requiredPerms = [];

    public function __construct(string $name, string $mode = 'read', string $type = 'text')
    {
        $this->name = $name;
        $this->mode = $mode;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getDisplayComponent(): string
    {
        return $this->type;
    }

    public static function readMode(string $name, string $type = 'text'): static
    {
        return new static($name, 'read', $type);
    }

    public static function editMode(string $name, string $type = 'text'): static
    {
        return new static($name, 'edit', $type);
    }

    public static function hideMode(string $name, string $type = 'text'): static
    {
        return new static($name, 'hide', $type);
    }

    public static function dataMode(string $name, string $type = 'text'): static
    {
        return new static($name, 'data', $type);
    }

    public function setRequiredPerms(array $perms): static
    {
        $this->requiredPerms = $perms;
        return $this;
    }
}