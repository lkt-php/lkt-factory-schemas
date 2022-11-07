<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Values\FieldOrderValue;

trait FieldWithOrderOptionTrait
{
    protected $order;

    /**
     * @param $order
     * @return FieldWithOrderOptionTrait
     */
    final public function setOrder($order): self
    {
        $this->order = new FieldOrderValue($order);
        return $this;
    }

    /**
     * @return array
     */
    final public function getOrder(): array
    {
        if ($this->order instanceof FieldOrderValue) {
            return $this->order->getValue();
        }
        return [];
    }
}