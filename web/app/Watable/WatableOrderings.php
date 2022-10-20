<?php

namespace App\Watable;

class WatableOrderings
{
    private $orderingList = [];

    public function add($fieldName, $order)
    {
        $this->orderingList[$fieldName] = $order;
        return $this;
    }

    public function get($fieldName)
    {
        return isset($this->orderingList[$fieldName]) ? $this->orderingList[$fieldName] : false;
    }
}

