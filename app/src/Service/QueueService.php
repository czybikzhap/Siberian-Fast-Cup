<?php

namespace App\Service;

use App\Service\Consumers\EmailConsumer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueService
{
    private AMQPStreamConnection $AMQPStreamConnection;
    public function __construct(AMQPStreamConnection $AMQPStreamConnection)
    {
        $this->AMQPStreamConnection = $AMQPStreamConnection;
    }

    public function publishMessage(string $text): void
    {

        $connection = $this->AMQPStreamConnection->getConnection();
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

        $channel->close();
        $connection->close();
    }

    public function receiverMessage(): void
    {

        $connection = $this->AMQPStreamConnection->getConnection();
        $channel = $connection->channel();

        $channel->queue_declare(
            'email',
            false,
            false,
            false,
            false
        );

        $callback = function (AMQPMessage $msg){
            foreach ($msg->get_properties() as $property)
                switch ($property){
                    case 'email':
                        try{
                            EmailConsumer::sendToEmail($msg->body);
                        }catch (\Exception $exception){
                            return $exception;
                        }
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