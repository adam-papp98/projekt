<?php

namespace App\Model\User;

class UserData
{
    public $username;
    public $password;
    public $firstname;
    public $lastname;

    public static function createFromUser(User $user)
    {
        $userdata = new static;
        $userdata->username = $user->getUsername();
        $userdata->firstname = $user->getFirstname();
        $userdata->lastname = $user->getLastname();
        return $userdata;

    }
}