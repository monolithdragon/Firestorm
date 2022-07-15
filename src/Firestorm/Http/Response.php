<?php
declare(strict_types=1);

namespace Firestorm\Http;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{
    /**
     * Wrapper method for dymfony http response
     *
     * @return HttpResponse
     */
    public function handler(): HttpResponse
    {
        if (!isset($response)) {
            $response = new HttpResponse;

            if ($response) {
                return $response;
            }
        }

        return false;
    }
}
