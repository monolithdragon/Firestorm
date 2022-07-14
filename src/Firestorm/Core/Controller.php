<?php

declare(strict_types=1);

namespace Firestorm\Core;

use Firestorm\Core\Exception\CoreLogicException;

class Controller
{
    /** @var array */
    protected array $routeParams;
    
    /** @var Object */
    private Object $twig;

    /**
     * Main class constructor
     *
     * @param  array $routeParams
     */
    public function __construct(array $routeParams)
    {
        $this->routeParams = $routeParams;
        $this->twig = new View;
    }

    /**
     * Renders a view template from sub controller classes
     *
     * @param  string $template
     * @param  array  $context
     * @return Response
     */
    public function render(string $template, array $context = [])
    {
        if ($this->twig === null) {
            throw new CoreLogicException('You cannot use the render method if the twig bundle is not available.');
        }

        return $this->twig->template($template, $context);
    }
}
