<?php
//数据库配置
return [
    // 默认数据库配置
    'default' => [
        'driver'    => 'mysql',// msyql驱动
        'host'      => 'localhost',// 主机
        'port'      => 3306,// 端口
        'user'      => 'root',// 账户
        'password'  => 'root',// 密码
        'dbname'    => 'event',// 数据库名
        'table_prefix'  => 'tbl_',// 表前缀
    ],
];