<?php

declare(strict_type=1);

namespace Firestorm\Router;

use Firestorm\Router\Exception\RouterException;
use Firestorm\Router\Exception\RouterMethodNotFoundException;

class Router implements RouterInterface
{
    /**
     * Returns an array of route from our routing table
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * Returns an array of route parameters
     *
     * @var array
     */
    protected array $params = [];

    /**
     * Adds a suffix onto the controller name
     *
     * @var string
     */
    protected string $controllerSuffix = 'controller';

    /**
     * @inheritDoc     
     */
    public function add(string $route, array $params): void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url): void
    {
        if ($this->match($url)) {
            $controllerClass = $this->params['controller'];
            $controllerClass = str_replace(' ', '', ucwords(str_replace('-', ' ', $controllerClass)));
            $controllerClass = $this->getNamespace($controllerClass);

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass($this->params);
                $action = $this->params['action'];
                $action = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action))));

                if (is_callable([$controller, $action])) {
                    $controller->$action();
                } else {
                    throw new RouterMethodNotFoundException();                    
                }
            } else {
                throw new RouterException();
            }
        } else {
            throw new RouterException();
        }
    }

    /**
     * Match the route to the routes in the routing table, settings the $this->params property
     * if a route is found
     *
     * @param  string  $url
     * @return boolean
     */
    private function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true; 
            }
        }

        return false;
    }

    /**
     * Get the namespace for the controller class.
     * The namespace defined within the route parameters only if it was added
     *
     * @param  string $string
     * @return string
     */
    private function getNamespace(string $string): string
    {
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}
