<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageHistory;
use App\Repository\messageHistoryRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DateTime;

class MessageController extends AutorizationController
{
    private UserRepository $userRepository;
    private MessageRepository $messageRepository;
    private MessageHistoryRepository $messageHistoryRepository;

    public function __construct(UserRepository $userRepository, MessageRepository $messageRepository, MessageHistoryRepository $messageHistoryRepository)
    {
        $this->userRepository           = $userRepository;
        $this->messageRepository        = $messageRepository;
        $this->messageHistoryRepository = $messageHistoryRepository;
    }

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
        $this->messageRepository->add($message, true);
        $this->messageHistoryRepository->add($messageHistory, true);

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