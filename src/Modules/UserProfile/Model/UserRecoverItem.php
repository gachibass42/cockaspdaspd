<?php

namespace App\Modules\UserProfile\Model;

class UserRecoverItem
{
    public ?string $username;
    public ?string $password;
    public ?string $token;
    public ?string $error;

    /**
     * @param string|null $username
     * @param string|null $password
     * @param string|null $token
     * @param string|null $error
     */
    public function __construct(?string $username, ?string $password, ?string $token, ?string $error)
    {
        $this->username = $username;
        $this->password = $password;
        $this->token = $token;
        $this->error = $error;
    }

}