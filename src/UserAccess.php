<?php

namespace UserAccess\Core;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Provider\ProviderInterface;

class UserAccess {

    private $provider;

    public function __construct(ProviderInterface $provider) {
        if (empty($provider)) {
            throw new \Exception('Provider mandatory');
        }
        $this->provider = $provider;
    }

    public function getProvider(): ProviderInterface {
        return $this->provider;
    }

}