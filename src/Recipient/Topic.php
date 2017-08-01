<?php

namespace sngrl\PhpFirebaseCloudMessaging\Recipient;

class Topic extends Recipient
{
    /**
     * @var string
     */
    private $name;

    /**
     * Topic constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}