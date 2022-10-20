<?php

namespace App\Watable;

use Fux\Database\Model\Model;

class Watable
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
        return $this->model::getPrimaryKey();
    }


    public function getJsonDataFromModel($listCb = null)
    {
        $data = array("cols" => array(), "rows" => array());
        $lista = is_callable($listCb) ? $listCb() : $this->model::listRecords();

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
                    if (preg_match("/{{3}(.*)}{3}/", $value)) {
                        $lista[$i][$key] = preg_replace("/({{3}|}{3})/", "", $value);
                    } else {
                        $lista[$i][$key] = htmlspecialchars($value);
                        if ($this->overrides && $o = $this->overrides->get($key)) {
                            if ($newVal = call_user_func($o, $row, $key, $value)) {
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
