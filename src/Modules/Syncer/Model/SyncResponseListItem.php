<?php

namespace App\Modules\Syncer\Model;

class SyncResponseListItem
{
    private string $type;
    private mixed $object;

    /**
     * @param string $type
     * @param mixed $object
     */
    public function __construct(string $type, mixed $object)
    {
        $this->type = $type;
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getObject(): mixed
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject(mixed $object): void
    {
        $this->object = $object;
    }


}