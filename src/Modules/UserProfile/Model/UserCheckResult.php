<?php

namespace App\Modules\UserProfile\Model;

class UserCheckResult
{
    public string $result;

    /**
     * @param string $result
     */
    public function __construct(string $result)
    {
        $this->result = $result;
    }


}