<?php

namespace App\Service;

use App\Service\Consumers\EmailConsumer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueService
{
    function publishMessage(string $text){
        $connection = new AMQPStreamConnection(
            'rabbitmq',
            5672,
            'rabbitmq',
            'rabbitmq'
        );
        $channel = $connection->channel();

        $channel->queue_declare(
            'email',
            false,
            false,
            false,
            false
        );

        //Публикация сообщения в очередь
        $message = new AMQPMessage($text, ['type' => 'email']);

        $channel->basic_publish($message,'','email');

        echo " [x] Sent email\n";

        $channel->close();
        $connection->close();
    }

    static function receiverMessage(){
        $connection = new AMQPStreamConnection(
            'rabbitmq',
            5672,
            'rabbitmq',
            'rabbitmq'
        );
        $channel = $connection->channel();

        $channel->queue_declare(
            'email',
            false,
            false,
            false,
            false
        );

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function (AMQPMessage $msg){
            echo ' [x] Received ', $msg->body, "\n";
            print_r($msg->get_properties());
            foreach ($msg->get_properties() as $property)
                switch ($property){
                    case 'email':
                        EmailConsumer::sendToEmail($msg->body);
                        break;
                }
        };

        $channel->basic_consume(
            'email',
            '',
            false,
            false,
            false,
            false,
            $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}