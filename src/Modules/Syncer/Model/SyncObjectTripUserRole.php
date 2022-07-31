<?php

namespace App\Modules\Syncer\Model;

class SyncObjectTripUserRole
{
    public string $userID;
    public string $roleName;

    /**
     * @param string $userID
     * @param string $roleName
     */
    public function __construct(string $userID, string $roleName)
    {
        $this->userID = $userID;
        $this->roleName = $roleName;
    }

}