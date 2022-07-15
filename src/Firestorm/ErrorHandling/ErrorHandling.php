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
        if ($error) {
            echo '<h1>Fatal Error</h1>';
            // echo '<p><b>Uncought exception:</b> ' . get_class($exception) . '</p>';
            // echo '<p><b>Message:</b> ' . $exception->getMessage() . '</p>';
            // echo '<p><b>StackTrace:</b></p><p>' . $exception->getTraceAsString() . '</p>';
            // echo '<p><b>Thrown in: ' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b></p>';
            echo (new self)->stackTrace($exception);
        } else {
            $errorLog = LOG_DIR . date('Y-m-d H:is') . '.txt';
            ini_set('error_log', $errorLog);
            $message = 'Uncought exception: ' . get_class($exception);
            $message .= 'With message ' . $exception->getMessage();
            $message .= '\nStack trace: ' . $exception->getTraceAsString();
            $message .= '\nThown in ' . $exception->getFile() . ' on line ' . $exception->getLine();

            error_log($message);

            echo (new View)->template("error/{$code}.html.twig", ['error_message' => $message]);
        }
    }

    private function stackTrace($exception, $seen = null)
    {
        $starter = $seen ? 'Caused by: ' : '';
        $result = array();
        if (!$seen) $seen = array();
        $trace  = $exception->getTrace();
        $prev   = $exception->getPrevious();
        $result[] = sprintf('<b>Uncought exception</b> %s%s: <b>%s</b> ', $starter, get_class($exception), $exception->getMessage());
        $file = $exception->getFile();
        $line = $exception->getLine();
        while (true) {
            $current = "$file:$line";
            if (is_array($seen) && in_array($current, $seen)) {
                $result[] = sprintf(' ... %d more', count($trace) + 1);
                break;
            }
            $result[] = sprintf(
                ' at %s%s%s(%s%s%s)',
                count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                $line === null ? $file : basename($file),
                $line === null ? '' : ':',
                $line === null ? '' : $line
            );
            if (is_array($seen))
                $seen[] = "$file:$line";
            if (!count($trace))
                break;
            $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
            $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
            array_shift($trace);
        }
        $result = join("\n", $result);
        if ($prev)
            $result  .= "\n" . $this->stackTrace($prev, $seen);

        return $result;
    }
}
