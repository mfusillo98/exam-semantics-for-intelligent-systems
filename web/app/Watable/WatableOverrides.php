<?php

namespace App\Watable;

class WatableOverrides
{
    private $overrideList = [];

    public function add($fieldName, callable $closure)
    {
        $this->overrideList[$fieldName] = $closure;
        return $this;
    }

    public function get($fieldName)
    {
        return isset($this->overrideList[$fieldName]) ? $this->overrideList[$fieldName] : false;
    }
}
