<?php

namespace App\Watable;

class WatableTemplates
{
    private $templateList = [];

    public function add($fieldName, $format)
    {
        $this->templateList[$fieldName] = $format;
        return $this;
    }

    public function get($fieldName)
    {
        return isset($this->templateList[$fieldName]) ? $this->templateList[$fieldName] : '';
    }
}