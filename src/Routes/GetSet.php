<?php

namespace Tualo\Office\UserCache\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Basic\IRoute;


class GetSet extends \Tualo\Office\Basic\RouteWrapper
{
    public static function scope(): string
    {
        return 'basic';
    }

    public static function register()
    {



        Route::add('/usercache/(?P<id>[\w.\/\-]+)', function ($matches) {
            TualoApplication::contenttype('application/json');
            TualoApplication::result('success', false);
            try {
                $userCache = \Tualo\Office\UserCache\UserCache::getInstance();
                $data = $userCache->getValue($matches['id']);
                if ($data !== false) {
                    TualoApplication::result('success', true);
                    TualoApplication::result('data', $data);
                } else {
                    http_response_code(404);
                    TualoApplication::result('msg', "Key not found");
                }
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['get'], true, [], self::scope());


        Route::add('/usercache', function () {
            TualoApplication::contenttype('application/json');
            TualoApplication::result('success', false);
            try {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!is_array($input)) {
                    http_response_code(400);
                    TualoApplication::result('msg', "Missing 'value' in request body");
                    return;
                }
                $userCache = \Tualo\Office\UserCache\UserCache::getInstance();
                $key = $userCache->setValue(json_encode($input));
                TualoApplication::result('success', true);
                TualoApplication::result('key', $key);
            } catch (\Exception $e) {
                TualoApplication::result('msg', $e->getMessage());
            }
        }, ['put'], true, [], self::scope());
    }
}
