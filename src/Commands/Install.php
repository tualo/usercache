<?php

namespace Tualo\Office\UserCache\Commands;

use Tualo\Office\Basic\ICommandline;
use Tualo\Office\Basic\CommandLineInstallSQL;


class Install extends CommandLineInstallSQL  implements ICommandline
{
    public static function getDir(): string
    {
        return dirname(__DIR__, 1);
    }
    public static $shortName  = 'usercache';
    public static $files = [


        'middlewares'                    => 'setup middlewares',


    ];
}
