<?php

$demo = '[demo:php lib/demo/CronRun.php demo_cron vendor/autoload.php]';
switch (true) {
    // 参数错误
    case empty($argv[1]):
        throw new Exception('模块key不能未空'.$demo);
        break;
    // 请传入引入composer autoload.php
    case empty($argv[2]):
        throw new Exception('引导文件不能为空(composer "vendor/autoload.php")'.$demo);
        break;
    default:
        list($moduleKey, $composerAutoload) = [$argv[1], $argv[2]];
        break;
}

require $composerAutoload;

\Wei\EventWork\Cron::run($moduleKey);