<?php

declare(strict_types=1);

namespace Firestorm\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Firestorm\Twig\TwigExtension;

class View
{
    /**
     * Get the contents of a view template using Twig
     *
     * @param  string $template
     * @param  array  $context
     * @return string
     */
    public function template(string $template, array $context = []): string
    {
        static $twig;
        if ($twig === null) {
            $loader = new FilesystemLoader('templates', TEMPLATE_PATH);
            $twig = new Environment($loader);
            $twig->addExtension(new DebugExtension);
            $twig->addExtension(new TwigExtension);
        }

        return $twig->render($template, $context);
    }
}
