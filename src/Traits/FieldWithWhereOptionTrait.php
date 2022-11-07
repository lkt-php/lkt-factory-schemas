<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Values\FieldWhereValue;

trait FieldWithWhereOptionTrait
{
    protected $where;

    /**
     * @param $where
     * @return FieldWithWhereOptionTrait
     */
    final public function setWhere($where): self
    {
        $this->where = new FieldWhereValue($where);
        return $this;
    }

    /**
     * @return array
     */
    final public function getWhere(): array
    {
        if ($this->where instanceof FieldWhereValue) {
            return $this->where->getValue();
        }
        return [];
    }
}