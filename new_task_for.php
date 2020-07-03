<?php
/**
 * User: BenHuang
 * Email: benhuang1024@gmail.com
 * Date: 2019-11-09
 * Time: 16:32
 */

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('local', 5672, 'user', 'password', '/vhost');
$channel = $connection->channel();
$channel->queue_declare('task_queue', false, true, false, false);
for ($i = 0; $i < 2; $i++) {
    $temp = [
        'skuId' => 'skuId',
        'spuId' => 'spuId',
    ];
    $data = json_encode($temp, JSON_UNESCAPED_UNICODE);
    $msg = new AMQPMessage($data,
        ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

    $channel->basic_publish($msg, '', 'task_queue');

    echo ' [x] Sent ', $data, "\n";
}
$channel->close();
$connection->close();