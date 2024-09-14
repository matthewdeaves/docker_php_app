<?php
error_reporting(0);
require __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'password');
    $channel = $connection->channel();
    $channel->queue_declare("test_queue", false, false, false, false);

    $message = json_encode([
        'key1' => 'value1',
        'key2' => 'value2'
    ]);
    
    $msg = new AMQPMessage($message);

    $channel->basic_publish($msg, '', "test_queue");

} catch (Exception $e) {
    echo "RabbitMQ exception: " . $e->getMessage();
}
