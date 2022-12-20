<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageHistory;
use App\Repository\messageHistoryRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DateTime;

class MessageController extends AutorizationController
{
    private UserRepository $userRepository;
    private MessageRepository $messageRepository;
    private MessageHistoryRepository $messageHistoryRepository;
    private Connection $connection;

    public function __construct(UserRepository $userRepository, MessageRepository $messageRepository, MessageHistoryRepository $messageHistoryRepository, Connection $connection)
    {
        $this->userRepository           = $userRepository;
        $this->messageRepository        = $messageRepository;
        $this->messageHistoryRepository = $messageHistoryRepository;
        $this->connection               = $connection;
    }

    /**
     * @throws Exception
     */
    public function sendMessage(Request $request, Response $response)
    {
        $user = $this->authorization($request, $this->userRepository);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly or not empty, user not found");

            return $response
                ->withStatus(422);
        }

        $receiver_user = $this->hasReceiver($request, $this->userRepository);
        if($receiver_user === null)
        {
            $response->getBody()->write("receiver_user_id not use or not empty, user not found");
            return $response
                ->withStatus(422);
        }

        $dataSend = new DateTime('now');

        $messageText = $this->hasMessageText($request);
        if($messageText === null)
        {
            $response->getBody()->write("Message text not use or not empty");
            return $response
                ->withStatus(422);
        }
        $message = new Message(
            $messageText,
            $user,
            $receiver_user,
            "отправлено",
            $dataSend
        );

        $messageHistory = new MessageHistory(
            $messageText,
            $user,
            $receiver_user,
            "отправлено",
            $dataSend
        );

        //TODO Транзакцию добавить
        try {
            $db = $this->connection;
        } catch (Exception $e) {
            die("Не удалось подключиться: " . $e->getMessage());
        }

        try{
            $db->beginTransaction();
            $this->messageRepository->add($message, true);
            $this->messageHistoryRepository->add($messageHistory, true);
            $db->commit();
        } catch (Exception $e){
            $db->rollBack();
            echo "Ошибка: " . $e->getMessage();
        }


        $response->getBody()->write("Сообщение отправленно успешно!");
        return $response
            ->withStatus(201);
    }

    public function getMessages(Request $request, Response $response)
    {
        $user = $this->authorization($request, $this->userRepository);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly or not empty, user not found");

            return $response
                ->withStatus(422);
        }

        $receiver_user = $this->hasReceiver($request, $this->userRepository);
        if($receiver_user === null)
        {
            $response->getBody()->write("receiver_user_id not use or not empty, user not found");
            return $response
                ->withStatus(422);
        }

        $message = $this->messageRepository->findArrayBy($user->getId(), $receiver_user->getId());
        $response->getBody()->write(json_encode($message));
        return $response
            ->withStatus(200);
    }
}