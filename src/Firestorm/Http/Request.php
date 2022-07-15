<?php

declare(strict_types=1);

namespace Firestorm\Http;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

class Request
{
    /**
     * Wrapper method for dymfony http request
     *
     * @return HttpRequest
     */
    public function handler(): HttpRequest
    {
        if (!isset($request)) {
            $request = new HttpRequest;

            if ($request) {
                $create = $request->createFromGlobals();
                if ($create) {
                    return $create;
                }
            }
        }

        return false;
    }
}
