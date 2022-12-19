<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Slim\Psr7\Request;


abstract class AutorizationController
{
    public function authorization(Request $request, UserRepository $userRepository): ?User{

        if($request->hasHeader('Token'))
        {
            $token = $request->getHeader( 'Token');
            $token = reset($token);
            return $userRepository->findOneByToken($token);
        }
        return null;
    }

    public function hasReceiver(Request $request, UserRepository $userRepository): ?User{

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

    public function hasMessageText(Request $request): ?string{

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

    public function hasFollower(Request $request, UserRepository $userRepository): ?User{

        $params = json_decode($request->getBody()->getContents(), true);

        if (array_key_exists ('follower_id', $params))
        {
            if(!empty($params['follower_id']))
            {
                return $userRepository->find($params['follower_id']);
            }
            return null;
        }
        return null;
    }
}