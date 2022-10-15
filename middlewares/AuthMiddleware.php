<?php

namespace grimm1994\grimmCore\middlewares;

use grimm1994\grimmCore\Application;
use grimm1994\grimmCore\exception\ForbiddenException;

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