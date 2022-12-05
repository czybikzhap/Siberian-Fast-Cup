<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DateTime;

class MessageController
{
    private UserRepository $userRepository;
    private MessageRepository $messageRepository;

    public function __construct(UserRepository $userRepository, MessageRepository $messageRepository)
    {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
    }

    public function sendMessage(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);

        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        $params = json_decode($request->getBody()->getContents(), true);

        if (array_key_exists ('receiver_user_id', $params))
        {
            if(empty($params['receiver_user_id']))
            {
                $response->getBody()->write("receiver_user_id not be empty or null");
                return $response
                    ->withStatus(422);
            }
        }else{
            $response->getBody()->write("receiver_user_id not use");
            return $response
                ->withStatus(400);
        }

        $receiver_user = $this->userRepository->find($params['receiver_user_id']);
        if($receiver_user === null)
        {
            $response->getBody()->write("User not found");
            return $response
                ->withStatus(422);
        }

        $dataSend = new DateTime('now');

        $message = new Message(
            $params['messages_text'],
            $user,
            $receiver_user,
            $params['status'],
            $dataSend
        );

        $this->messageRepository->add($message, true);


        $response->getBody()->write("Сообщение отправленно успешно!");
        return $response
            ->withStatus(201);
    }

    public function showMessage(Request $request, Response $response)
    {
        if(!$request->hasHeader('Token'))
        {
            $response->getBody()->write("Not authorized");

            return $response
                ->withStatus(401);
        }

        $token = $request->getHeader( 'Token');
        $token = reset($token);

        $user = $this->userRepository->findOneByToken($token);
        if($user === null)
        {
            $response->getBody()->write("Token entered incorrectly");

            return $response
                ->withStatus(422);
        }

        // TODO getMessage
    }
}