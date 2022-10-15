<?php

namespace grimm1994\grimmCore;

use grimm1994\grimmCore\exception\NotFoundException;

class Router
{
    protected array $routes = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(public Request $request, public Response $response)
    {

    }

    public function get(string $path, callable | string | array $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, callable | string | array $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            Application::$app->setController(new $callback[0]());
            Application::$app->getController()->setAction($callback[1]);
            $controller = Application::$app->getController();
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}