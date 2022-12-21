<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Slim\Psr7\Request;

abstract class WithAuthorizationController
{
    abstract protected function getUserRepository(): UserRepository;
    protected function authorization(Request $request): ?User
    {
        if($request->hasHeader('Token'))
        {
            $token = $request->getHeader( 'Token');
            $token = reset($token);
            return $this->getUserRepository()->findOneByToken($token);
        }
        return null;
    }
}