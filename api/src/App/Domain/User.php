<?php

namespace App\Domain;

class User
{
    private $owner;
    private $token;

    public function __construct(array $owner, $token)
    {
        $this->owner = $owner;
        $this->token = $token;
    }

    public function getUsername()
    {
        return $this->owner['login'];
    }

    public function getToken()
    {
        return $this->token;
    }
}
