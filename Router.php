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

    private function getRoutes(string $method): array
    {
        return $this->routes[$method] ?? [];
    }

    private function getCallback(): array | false
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();

        // Trim slashes
        $url = trim($url, '/');

        // Get all routes for current request method
        $routes = $this->getRoutes($method);

        $routeParams = false;

        // Start iterating registed routes
        foreach ($routes as $route => $callback) {
            // Trim slashes
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) {
                continue;
            }

            // Find all route names from the route and save in $routeNames
            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            // Convert route name into regex pattern
            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";

            // Test and match current route against $routeReges
            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];
                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine($routeNames, $values);

                $this->request->setRouteParams($routeParams);

                return $callback;
            }
        }

        return false;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        $path = $this->request->getUrl();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            $callback = $this->getCallback();
            if ($callback === false) {
                throw new NotFoundException();
            }
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