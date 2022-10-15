<?php

namespace grimm\grimmmvc\middlewares;

use grimm\grimmmvc\Application;
use grimm\grimmmvc\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public function __construct(public array $actions = [])
    {
    }

    /**
     * @throws ForbiddenException
     */
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->getController()->getAction(), $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}