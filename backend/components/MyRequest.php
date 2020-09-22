<?php

namespace backend\components;

class MyRequest extends \yii\web\Request
{
    public $web = '/backend/web';
    public $adminUrl;
    public static $getBaseUrl;
    public static $getActualBaseUrl;
    public static $resolvePathInfo;

    public function getBaseUrl()
    {
        return $getBaseUrl = str_replace($this->web, "", parent::getBaseUrl()) . $this->adminUrl;
    }

    public function getActualBaseUrl()
    {
        return $getActualBaseUrl = str_replace($this->web, "", parent::getBaseUrl());
    }

    public function resolvePathInfo()
    {
        if ($this->getUrl() === $this->adminUrl) {
            return $resolvePathInfo = "";
        } else {
            return $resolvePathInfo = parent::resolvePathInfo();
        }
    }
}
