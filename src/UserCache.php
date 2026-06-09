<?php

namespace Tualo\Office\UserCache;

use Tualo\Office\Basic\TualoApplication as App;

class UserCache
{
    private static bool $initalized = false;
    private static ?UserCache $instance = null;

    private static string $host = '127.0.0.1';
    private static int $port = 6379;
    private static int $db = 1;
    private static string $prefix = 'user_cache';
    private static int $keyLength = 36; // characters
    private static int $lifetime = 1442; // seconds


    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
            self::$instance->initialize();
        }
        return self::$instance;
    }

    private function initialize(): void
    {
        if (!self::$initalized) {
            self::$host = App::configuration('usercache', 'redis_host', '127.0.0.1');
            self::$port = App::configuration('usercache', 'redis_port', 6379);
            self::$db = App::configuration('usercache', 'redis_db', 1);
            self::$prefix = App::configuration('usercache', 'key_prefix', 'user_cache');
            self::$keyLength = App::configuration('usercache', 'key_length', 36);
            self::$lifetime = App::configuration('usercache', 'key_lifetime', 1442);
            self::$initalized = true;
        }
    }

    private function getRedis(): \Redis
    {
        $redis = new \Redis();
        $redis->connect(self::$host, self::$port);
        $redis->select(self::$db);
        return $redis;
    }

    /**
     * Get possible keys from request
     * @return array<string>
     */
    private function getPossibleKeys(): array
    {
        $result = [];
        foreach ($_REQUEST as $key => $value) {
            if (strpos($key, self::$prefix . '_') === 0) {
                if (strlen($key) === strlen(self::$prefix . '_') + self::$keyLength) {
                    $result[] = $key;
                }
            }
        }
        return $result;
    }

    /**
     * Set value in Redis
     * @param string $id
     * @return string Generated key
     */
    public function setValue(string $value = '', string $key = ''): string
    {
        $redis = $this->getRedis();


        if ($key == '') {
            $key = self::$prefix . '_' . bin2hex(random_bytes(self::$keyLength / 2));
        }
        $redis->setex($key, self::$lifetime, $value);
        return $key;
    }

    private function removeOldKeys(string $id): void
    {
        $redis = $this->getRedis();
        $data = $redis->keys(self::$prefix . '_*');
        $keys = [];
        foreach ($data as $key) {
            $value = $redis->get($key);
            if ($value === $id) {
                $keys[] = $key;
            }
        }
        foreach ($keys as $key) {
            $redis->del($key);
        }
    }


    public function getValue(string $key): string|false
    {

        $redis = $this->getRedis();
        $data = $redis->get($key);
        return $data;
    }
}
