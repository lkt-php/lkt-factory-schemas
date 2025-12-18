<?php

namespace Lkt\Factory\Schemas\Views\Layouts;

/**
 * @deprecated
 */
class GridLayout extends SchemaLayout
{
    protected int $amountOfItems = 0;
    protected array $content = [];

    public static function define(string $name, int $amountOfItems, array $content): static
    {
        $r = new static();
        $r->name = $name;
        $r->amountOfItems = $amountOfItems;
        $r->content = $content;
        return $r;
    }

    public function setBox(string $title, string $component): static
    {
        $this->boxTitle = $title;
        $this->boxComponent = $component;
        return $this;
    }

    public function setAmountOfItems(int $amountOfItems): static
    {
        $this->amountOfItems = $amountOfItems;
        return $this;
    }

    public function setContent(array $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function toArray(): array
    {
        $r = [
            'name' => $this->name,
            'type' => 'grid',
            'amountOfItems' => $this->amountOfItems,
            'content' => [],
            'conditionalModes' => $this->conditionalModes,
            'conditionalTypes' => $this->conditionalTypes,
            'conditionallyHidden' => $this->conditionallyHidden,
            'conditionallyVisible' => $this->conditionallyVisible,
            'boxTitle' => $this->boxTitle,
            'boxComponent' => $this->boxComponent,
        ];

        foreach ($this->content as $item) {
            if ($item instanceof SchemaLayout) {
                $r['content'][] = $item->toArray();
            } else {
                $r['content'][] = $item;
            }
        }

        return $r;
    }
}