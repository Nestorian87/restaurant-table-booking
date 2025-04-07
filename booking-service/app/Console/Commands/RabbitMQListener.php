<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
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
        $channel->exchange_declare('table_types', 'topic', false, true, false);
        $channel->exchange_declare('restaurants', 'topic', false, true, false);

        $channel->queue_declare('booking.users', false, true, false, false);
        $channel->queue_bind('booking.users', 'users', 'user.*');

        $channel->basic_consume('booking.users', '', false, true, false, false, function ($msg) {
            $payload = json_decode($msg->body, true);
            $event = $payload['event'] ?? '';
            $data = $payload['data'] ?? [];

            match ($event) {
                'user.created', 'user.updated' => User::updateOrCreate(['id' => $data['id']], $data),
                'user.deleted' => User::where('id', $data['id'])->delete(),
                default => logger()->warning("Unknown user event: $event")
            };
        });

        $channel->queue_declare('booking.table_types', false, true, false, false);
        $channel->queue_bind('booking.table_types', 'table_types', 'table_type.*');

        $channel->basic_consume('booking.table_types', '', false, true, false, false, function ($msg) {
            $payload = json_decode($msg->body, true);
            $event = $payload['event'] ?? '';
            $data = $payload['data'] ?? [];

            match ($event) {
                'table_type.created', 'table_type.updated' => TableType::updateOrCreate(['id' => $data['id']], $data),
                'table_type.deleted' => TableType::where('id', $data['id'])->delete(),
                default => logger()->warning("Unknown table_type event: $event")
            };
        });

        $channel->queue_declare('booking.restaurants', false, true, false, false);
        $channel->queue_bind('booking.restaurants', 'restaurants', 'restaurant.*');
        $channel->basic_consume('booking.restaurants', '', false, true, false, false, function ($msg) {
            $payload = json_decode($msg->body, true);
            $event = $payload['event'] ?? '';
            $data = $payload['data'] ?? [];

            match ($event) {
                'restaurant.created', 'restaurant.updated' => (function () use ($event, $data, $msg) {
                    $restaurant = Restaurant::updateOrCreate(
                        ['id' => $data['id']],
                        [
                            'name' => $data['name'],
                            'max_booking_places' => $data['max_booking_places']
                        ]
                    );

                    if (isset($data['working_hours']) && is_array($data['working_hours'])) {
                        $restaurant->workingHours()->delete();

                        foreach ($data['working_hours'] as $item) {
                            $restaurant->workingHours()->create($item);
                        }
                    }
                })(),
                'restaurant.deleted' => Restaurant::where('id', $data['id'])->delete(),

                default => logger()->warning("Unknown restaurant event: $event")
            };
        });

        $this->info('Waiting for messages...');

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
