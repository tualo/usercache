<?php

namespace Tualo\Office\UserCache\CMSMiddleware;

use Tualo\Office\UserCache\UserCache as O;

class UserCache
{

    public static function request(): mixed
    {
        return function (): O {
            return O::getInstance();
        };
    }


    public static function run(&$request, &$result)
    {
        $result['user_cache'] = self::request();
    }
}
