<?php

namespace grimm1994\grimmCore;

use grimm1994\grimmCore\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';
    private string $action = '';

    /**
     * @var \grimm1994\grimmCore\middlewares\BaseMiddleware[]
     */
    private array $middlewares = [];

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }
    
    public function render(string $view, array $params = []): string
    {
        return Application::$app->view->renderView($view, $params);
    }

    protected function registerMiddleware(BaseMiddleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }


}