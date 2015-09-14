# iron.io driver for pmg/queue
==============================

[![Latest Stable Version](https://poser.pugx.org/spekkionu/ironmq-queue-driver/v/stable.png)](https://packagist.org/packages/spekkionu/ironmq-queue-driver)
[![Total Downloads](https://poser.pugx.org/spekkionu/ironmq-queue-driver/downloads.png)](https://packagist.org/packages/spekkionu/ironmq-queue-driver)
[![Build Status](https://travis-ci.org/spekkionu/ironmq-queue-driver.svg?branch=master)](https://travis-ci.org/spekkionu/ironmq-queue-driver)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d71544a6-297b-4571-9ec5-7777e4d27def/mini.png)](https://insight.sensiolabs.com/projects/d71544a6-297b-4571-9ec5-7777e4d27def)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spekkionu/ironmq-queue-driver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spekkionu/ironmq-queue-driver/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/spekkionu/ironmq-queue-driver/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/spekkionu/ironmq-queue-driver/?branch=master)

```php
<?php
use IronMQ\IronMQ;
use PMG\Queue\Serializer\NativeSerializer;
use Spekkionu\PMG\Queue\Iron\Driver\IronDriver;

$ironmq = new IronMQ(array(
    "token" => 'API_TOKEN',
    "project_id" => 'PROJECT_ID'
));
$serializer = new NativeSerializer();
$driver = new IronDriver($ironmq, $serializer, [
    'timeout' => 2,
    'delay' => 0
]);

```
