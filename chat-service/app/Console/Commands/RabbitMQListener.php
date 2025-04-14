<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\User;
use App\Models\TableType;
use App\Models\WorkingHour;

class RabbitMQListener extends Command
{
    protected $signature = 'rabbitmq:listen';
    protected $description = 'Listen to RabbitMQ events';

    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest'),
            env('RABBITMQ_VHOST', '/')
        );

        $channel = $connection->channel();

        $channel->exchange_declare('users', 'topic', false, true, false);

        $channel->queue_declare('chat.users', false, true, false, false);
        $channel->queue_bind('chat.users', 'users', 'user.*');

        $channel->basic_consume('chat.users', '', false, true, false, false, function ($msg) {
            $payload = json_decode($msg->body, true);
            $event = $payload['event'] ?? '';
            $data = $payload['data'] ?? [];

            match ($event) {
                'user.created', 'user.updated' => User::updateOrCreate(['id' => $data['id']], $data),
                'user.deleted' => User::where('id', $data['id'])->delete(),
                default => logger()->warning("Unknown user event: $event")
            };
        });

        $this->info('Waiting for messages...');

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
