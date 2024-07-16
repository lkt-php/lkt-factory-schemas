<?php

namespace Lkt\Factory\Schemas\Views\Layouts;

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
            'type' => 'grid',
            'amountOfItems' => $this->amountOfItems,
            'content' => [],
            'conditionalModes' => $this->conditionalModes,
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