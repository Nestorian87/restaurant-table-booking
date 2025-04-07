<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest'),
            env('RABBITMQ_VHOST', '/')
        );

        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare('users', 'topic', false, true, false);
    }


    public function publishUserEvent(string $eventType, array $data): void
    {
        $this->publish('users', "user.$eventType", $data);
    }


    protected function publish(string $exchange, string $routingKey, array $data): void
    {
        $message = new AMQPMessage(json_encode([
            'event' => $routingKey,
            'data' => $data
        ]), ['delivery_mode' => 2]);

        $this->channel->basic_publish($message, $exchange, $routingKey);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
