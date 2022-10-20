<?php

namespace App\Watable;

use Fux\Database\Model\Model;

class WatableActions
{

    private $closure = null;
    private $name = '';

    public function __construct(string $name, callable $closure)
    {
        $this->closure = $closure;
        $this->name = $name;
    }

    public function html($record)
    {
        return call_user_func($this->closure, $record, $this->name);
    }

    public static function getDefault($model)
    {
        $actions = [];
        $actions[] = new WatableActions('edit', function ($row, $actionName) use ($model) {
            $pk = $row[$model::getPrimaryKey()[0]];
            return "<button class='btn btn-sm btn-primary mx-1' data-pk='$pk' data-role='watable-action-$actionName' data-toggle='tooltip' title='Modifica'><i class='fa fa-edit'></i></button>";
        });
        $actions[] = new WatableActions('delete', function ($row, $actionName) use ($model) {
            $pk = $row[$model::getPrimaryKey()[0]];
            return "<button class='btn btn-sm btn-danger mx-1' data-pk='$pk' data-role='watable-action-$actionName' data-toggle='tooltip' title='Modifica'><i class='fa fa-trash'></i></button>";
        });
        return $actions;
    }


}
