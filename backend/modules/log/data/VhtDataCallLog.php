<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20-May-19
 * Time: 3:04 PM
 */

namespace backend\modules\log\data;

use yii\base\InvalidArgumentException;
use yii\data\BaseDataProvider;
use yii\data\Pagination;

class VhtDataCallLog extends BaseDataProvider
{
    public $key;

    public $allModels;

    public $total;

    private $_keys;
    private $_models;
    private $_totalCount;
    private $_pagination;

    public function prepare($forcePrepare = false)
    {
        if ($forcePrepare || $this->_models === null) {
            $this->_models = $this->prepareModels();
        }
        if ($forcePrepare || $this->_keys === null) {
            $this->_keys = $this->prepareKeys($this->_models);
        }
    }

    protected function prepareModels()
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();

            if ($pagination->getPageSize() > 0) {
                $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit(), true);
            }
        }

        return $models;
    }

    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        }

        return array_keys($models);
    }

    protected function prepareTotalCount()
    {
        return $this->total;
    }

    public function getPagination()
    {
        if ($this->_pagination === null) {
            $this->setPagination([]);
        }

        return $this->_pagination;
    }

    public function setPagination($value)
    {
        if (is_array($value)) {
            $config = ['class' => Pagination::class];
            if ($this->id !== null) {
                $config['pageParam'] = $this->id . '-page';
                $config['pageSizeParam'] = $this->id . '-per-page';
            }
            $this->_pagination = \Yii::createObject(array_merge($config, $value));
        } elseif ($value instanceof Pagination || $value === false) {
            $this->_pagination = $value;
        } else {
            throw new InvalidArgumentException('Only Pagination instance, configuration array or false is allowed.');
        }
    }

    public function getTotalCount()
    {
        if ($this->getPagination() === false) {
            return $this->getCount();
        } elseif ($this->_totalCount === null) {
            $this->_totalCount = $this->prepareTotalCount();
        }

        return $this->_totalCount;
    }

    public function getCount()
    {
        return count($this->getModels());
    }

    public function getModels()
    {
        $this->prepare();

        return $this->_models;
    }

    public function setTotalCount($value)
    {
        $this->_totalCount = $value;
    }

    public function refresh()
    {
        $this->_totalCount = null;
        $this->_models = null;
        $this->_keys = null;
    }
}
