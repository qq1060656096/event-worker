# Event Worker
![版本1流程图](dev/images/1.x/1.x-flow-diagram.png)

# 事件发送demo
```php
<?php
$sendData = [
    'productId' => 1,// 购买产品id
    'quantity' => 10,// 购买数量
    'couponId' => 0,// 优惠券id
    'uid' => 10,//购买用户
];
//发送产品购买事件
\Wei\EventWork\EventSend::send('product_buy', $sendData);
```

# 单元测试使用

> --bootstrap 在测试前先运行一个 "bootstrap" PHP 文件
- --bootstrap引导测试: phpunit --bootstrap vendor/autoload.php tests/
- --bootstrap引导测试: phpunit --bootstrap tests/TestInit.php tests/

