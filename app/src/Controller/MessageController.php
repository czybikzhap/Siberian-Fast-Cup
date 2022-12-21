<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageHistory;
use App\Entity\User;
use App\Repository\MessageHistoryRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DateTime;

class MessageController extends WithAuthorizationController
{
    private UserRepository $userRepository;
    private MessageRepository $messageRepository;
    private MessageHistoryRepository $messageHistoryRepository;
    private Connection $connection;

    public function __construct(
        UserRepository $userRepository,
        MessageRepository $messageRepository,
        MessageHistoryRepository $messageHistoryRepository,
        Connection $connection)
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
        $user = $this->authorization($request);
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

        try {
            $this->connection->beginTransaction();
            $this->messageRepository->add($message, true);
            $this->messageHistoryRepository->add($messageHistory, true);
            $this->connection->commit();
        } catch (Exception $e){
            $this->connection->rollBack();
            echo "Ошибка: " . $e->getMessage();
        }

        $response->getBody()->write("Сообщение отправленно успешно!");
        return $response
            ->withStatus(201);
    }

    public function getMessages(Request $request, Response $response)
    {
        $user = $this->authorization($request);
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

    public function hasReceiver(Request $request, UserRepository $userRepository): ?User
    {

        $params = json_decode($request->getBody()->getContents(), true);

        if (array_key_exists ('receiver_user_id', $params))
        {
            if(!empty($params['receiver_user_id']))
            {
                return $userRepository->find($params['receiver_user_id']);
            }
            return null;
        }
        return null;
    }

    public function hasMessageText(Request $request): ?string
    {

        $params = json_decode($request->getBody()->getContents(), true);

        if (array_key_exists ('messages_text', $params))
        {
            if(!empty($params['messages_text']))
            {
                return $params['messages_text'];
            }
            return null;
        }
        return null;
    }
    protected function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }
}