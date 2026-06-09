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
        return function (string $value, string $key = ''): string {
            $uc = UserCache::getInstance();
            return $uc->setValue($value, $key);
        };
    }
}
