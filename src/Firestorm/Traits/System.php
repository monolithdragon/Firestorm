<?php

declare(strict_types=1);

namespace Firestorm\Traits;

use Firestorm\Core\Exception\CoreLogicException;
use Firestorm\GlobalManager\GlobalManager;
use Firestorm\Session\SessionManager;

trait System
{
    public static function sessionInit(bool $useSessionGlobal = false)
    {
        $session = SessionManager::initialize();
        if (!$session) {
            throw new CoreLogicException('Please enable session within your session.yaml conficuration file.');
        } elseif ($useSessionGlobal) {
            GlobalManager::set('global_session', $session);
        }

        return $session;
    }
}
