<?php

declare(strict_types=1);

namespace Firestorm\ErrorHandling;

use ErrorException;
use Firestorm\Core\View;

class ErrorHandling
{
    /**
     * Convert all errors to exception by throwing on ErrorException
     *
     * @param  int $severity
     * @param  string $message
     * @param  string|null $file
     * @param  int $line
     * @return void
     * @throws ErrorException
     */
    public static function errorHandler(int $severity, string $message, string $file, int $line)
    {
        if (!(error_reporting() && $severity)) {
            return;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }

        http_response_code($code);

        $error = true;
        if ( $error) {
            echo '<h1>Fatal Error</h1>';
            echo '<p>Uncought exception: ' . get_class($exception) . '</p>';
            echo '<p>Message: ' . $exception->getMessage() . '</p>';
            echo '<p>StackTrace: ' . $exception->getTraceAsString() . '</p>';
            echo '<p>Thrown in : ' . $exception->getFile() . ' on line ' . $exception->getLine() . '</p>';
        } else {
            $errorLog = LOG_DIR . '/' . date('Y-m-d H:is') . '.txt';
            ini_set('error_log', $errorLog);
            $message = 'Uncought exception: ' . get_class($exception);
            $message .= 'With message ' . $exception->getMessage();
            $message .= '\nStack trace: ' . $exception->getTraceAsString();
            $message .= '\nThown in ' . $exception->getFile() . ' on line ' . $exception->getLine();

            error_log($message);

            echo (new View)->template("error/{$code}.html.twig", ['error_message' => $message]);
        }
    }
}
