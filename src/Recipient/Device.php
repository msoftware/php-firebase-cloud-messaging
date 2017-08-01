<?php

namespace sngrl\PhpFirebaseCloudMessaging\Recipient;

class Device extends Recipient
{
    /**
     * @var string
     */
    private $token;

    /**
     * Device constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}