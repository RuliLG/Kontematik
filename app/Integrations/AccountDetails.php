<?php

namespace App\Integrations;

use Illuminate\Support\Arr;
use ReflectionClass;
use Illuminate\Support\Str;

class AccountDetails {
    public const USER = 'user';
    public const SITE_ID = 'site_id';
    public const SITE_NAME = 'site_name';

    public function __construct($data)
    {
        $this->data = $data;
        $this->map_ = [];
    }

    public function map($dataKey, $objKey)
    {
        $this->map_[$objKey] = $dataKey;
        return $this;
    }

    public function __call($name, $args)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        foreach ($constants as $constant => $value) {
            $methodName = 'get' . ucfirst(Str::camel($value));
            if ($name === $methodName) {
                $key = Arr::get($this->map_, $value);
                return isset($this->data[$key]) ? $this->data[$key] : null;
            }
        }

        return null;
    }
}
