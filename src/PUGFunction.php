<?php

namespace Tualo\Office\UserCache;

use Tualo\Office\PUG\IPUGFunction;
use Tualo\Office\UserCache\UserCache;

class PUGFunction implements IPUGFunction
{

    public static function register()
    {

        return [
            'pug_name' => 'usercache',
            'function' => self::fn()
        ];
    }

    public static function fn(): mixed
    {
        return function (string $key = '', string $value = ''): string {
            $uc = UserCache::getInstance();
            if ($key === '' && $value === '') {
                return "No key or value provided";
            }
            if ($key === '') {
                return $uc->setValue($value);
            } else {
                if ($value === '') {
                    return $uc->getValue($key);
                }
            }
            return $uc->setValue($value, $key);
        };
    }
}
