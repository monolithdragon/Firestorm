<?php

declare(strict_type=1);

namespace Firestorm\Router;

interface RouterInterface
{
    /**
     * Add a route to the routing table
     *
     * @param  string $rout
     * @param  array  $params
     * @return void
     */
    public function add(string $rout, array $params): void;

    /**
     * Dispatch route and create controller objects and execute the default method
     * on the controller object
     *
     * @param  string $url
     * @return void
     */
    public function dispatch(string $url): void;
}
