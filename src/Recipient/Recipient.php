<?php

namespace sngrl\PhpFirebaseCloudMessaging\Recipient;

class Recipient
{
    /**
     * @var Device|Topic
     */
    private $to;

    /**
     * @param Device|Topic $to
     * @return Recipient
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return Device|Topic
     */
    public function toJson()
    {
        return $this->to;
    }
}