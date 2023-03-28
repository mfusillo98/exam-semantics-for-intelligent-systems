<?php

require_once __DIR__ . '/../php/FuxFramework/bootstrap.php';
require_once __DIR__ . '/../php/FuxFramework/Service/IServiceProvider.php';

use Fux\DB;


class WatableService extends FuxServiceProvider implements IServiceProvider
{

    const PLACEHOLDER_COLUMN_NAME = '__WATABLE_PLACEHOLDER_ROW';

    private $model = null;
    private $removeFields = [];
    private $useFields = [];
    private $actions = null;
    private $templates = null;
    private $overrides = null;
    private $actionColumnName = "Azioni";
    private $orderings = null;

    public static function bootstrap()
    {
        // TODO: Implement bootstrap() method.
    }

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function removeFields($fields)
    {
        $this->removeFields = $fields;
        return $this;
    }

    public function useFields($fields)
    {
        $this->useFields = $fields;
        return $this;
    }

    public function setActions($actions = [])
    {
        $this->actions = $actions;
        return $this;
    }

    public function setTemplates(WatableTemplates $templates)
    {
        $this->templates = $templates;
        return $this;
    }

    public function setOverrides(WatableOverrides $overrides)
    {
        $this->overrides = $overrides;
        return $this;
    }

    public function setOrderings(WatableOrderings $orderings)
    {
        $this->orderings = $orderings;
        return $this;
    }

    private function getPkFields()
    {
        if ($this->model instanceof FuxModel) {
            return $this->model->getPkField();
        } elseif (is_subclass_of($this->model, \Fux\Database\Model\Model::class)) {
            return $this->model::getPrimaryKey();
        }
    }


    public function getJsonDataFromModel($listCb = null)
    {
        $data = array("cols" => array(), "rows" => array());
        $lista = is_callable($listCb) ? $listCb() : $this->model->listRecords();

        //Si tratta di una tabella con paginazione
        if (isset($lista['total'])) {
            $data['total'] = $lista['total']; //Salvo nei dati da inviare al client il totale delle righe
            $lista = $lista['data']; //Setto la $lista alla lista reale di righe della tabella
        }

        if ($this->actions === null)
            $actions = WatableActions::getDefault($this->model);
        else
            $actions = $this->actions;


        $isPlaceholderData = false; //Mi dice se il valore di ritorno sarà inteso come un placeholder

        foreach ($lista as $i => $row) {
            foreach ($lista[$i] as $key => $value) {
                if (in_array($key, $this->removeFields) || (!empty($this->useFields) && !in_array($key, $this->useFields))) {
                    unset($lista[$i][$key]);
                } else {
                    if (preg_match("/{{3}(.*)}{3}/", ($value ?? ""))) {
                        $lista[$i][$key] = preg_replace("/({{3}|}{3})/", "", $value);
                    } else {
                        $lista[$i][$key] = htmlspecialchars(($value ?? ""));
                        if ($this->overrides && $o = $this->overrides->get($key)) {
                            $newVal = call_user_func($o, $row, $key, $value);
                            if ($newVal !== false) {
                                $lista[$i][$key] = $newVal;
                            }
                        }
                    }
                }
            }

            if ($actions) {
                $lista[$i][$this->actionColumnName] = "";
                foreach ($actions as $a) {
                    if ($a instanceof WatableActions) {
                        $lista[$i][$this->actionColumnName] .= $a->html($row);
                    }
                }
            }

            if (isset($lista[$i][self::PLACEHOLDER_COLUMN_NAME])) {
                $isPlaceholderData = true;
                unset($lista[$i][self::PLACEHOLDER_COLUMN_NAME]);
            }

            $data['rows'][] = $lista[$i];
        }

        $lastColumnPosition = 1;
        if (isset($data['rows'][0])) {
            foreach ($data['rows'][0] as $key => $value) {
                $data['cols'][$key] = array(); //Creo la descrizione della colonna
                $columnPosition = $this->orderings && $this->orderings->get($key) ? $this->orderings->get($key) : ++$lastColumnPosition;
                $data['cols'][$key]['index'] = $columnPosition;
                $lastColumnPosition = $columnPosition;
                $data['cols'][$key]['friendly'] = ucwords(str_replace("_", " ", $key));
                if ($this->templates) {
                    if ($t = $this->templates->get($key)) {
                        $data['cols'][$key]['format'] = $t;
                    }
                }
                $data['cols'][$key]['sorting'] = true;
                $data['cols'][$key]['placeHolder'] = "Cerca nella colonna...";
                if ($key == $this->actionColumnName) $data['cols'][$key]['index'] = 1;
                if ($key == $this->getPkFields()[0]) $data['cols'][$key]['unique'] = true;
            }
        }


        //Verifico se l'unica riga che ho è una riga placeholder
        if ($isPlaceholderData) {
            $data['rows'] = [];
        }

        return $data;
    }
}

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

class WatableOverrides
{
    private $overrideList = [];

    /**
     * Add an override option to the override configuration
     * @param string $fieldName
     * @param callable $closure ($row, $key, $value)
    */
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

    public static function getDefault(FuxModel $model)
    {
        $actions = [];
        $actions[] = new WatableActions('edit', function ($row, $actionName) use ($model) {
            $pk = $row[$model->getPkField()[0]];
            return "<button class='btn btn-sm btn-primary mx-1' data-pk='$pk' data-role='watable-action-$actionName' data-toggle='tooltip' title='Modifica'><i class='fa fa-edit'></i></button>";
        });
        $actions[] = new WatableActions('delete', function ($row, $actionName) use ($model) {
            $pk = $row[$model->getPkField()[0]];
            return "<button class='btn btn-sm btn-danger mx-1' data-pk='$pk' data-role='watable-action-$actionName' data-toggle='tooltip' title='Modifica'><i class='fa fa-trash'></i></button>";
        });
        return $actions;
    }


}
