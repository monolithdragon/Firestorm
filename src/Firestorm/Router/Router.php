<?php

declare(strict_type=1);

namespace Firestorm\Router;

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
    public function add(string $rout, array $params): void
    {
        $this->routes[$rout] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url): void
    {
        if ($this->match($url)) {
            $controllerString = $this->params['controller'];
            $controllerString = str_replace(' ', '', ucwords(str_replace('-', ' ', $controllerString)));
            $controllerString = $this->getNamespace($controllerString);

            if (class_exists($controllerString)) {
                $controllerObject = new $controllerString;
                $action = $this->params['action'];
                $action = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action))));

                if (is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new \Exception();                    
                }
            } else {
                throw new \Exception();
            }
        } else {
            throw new \Exception();
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
