<?php

/**
 *
 * Error Handler for Bingo Framework
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

use Core\Views;
use App\Config;

class Error
{
	/**
	 *
	 * @param int $level Error level for the runtime of the script
	 * @param string $message Error message for the error triggered
	 * @param string $file
	 * @param string $line
	 *
	 * @return void
	 *
	 */

	public static function errorHandler($level, $message, $file, $line)
	{
		if (error_reporting() !== 0) {
			throw new \ErrorException($message, 0, $level, $file, $line);
		}
	}

	/**
	 *
	 * Performs an action based on the error condition specified
	 * @see Bingo/Core/Config.php
	 *
	 * @param string $exception
	 *
	 */

	public static function exceptionHandler($exception)
	{
		if (Config::SHOW_ERRORS === true) {
            switch (Config::ERROR_TYPE) {
                case 'json':
                    echo json_encode([
                        'type' => 'Fatal Error',
                        'uncaught_exception' => get_class($exception),
                        'error_specifics' => [
                            'stack_trace' => $exception->getTraceAsString(),
                            'line_thrown_in' => $exception->getLine(),
                            'file_thrown_in' => $exception->getFile(),
                            'error_code' => !is_null($exception->getCode()) ? $exception->getCode() : 200
                        ],
                        'error_message' => $exception->getMessage()
                    ]);
                    break;

                case 'text-html':
                    echo "
                        <h1>Fatal Error</h1>
                        <p>Uncaught exception: ".get_class($exception)."</p>
                        <p>Message: {$exception->getMessage()}</p>
                        <p>Stack Trace: {$exception->getTraceAsString()}</p>
                        <p>Thrown in: {$exception->getFile()}</p>
                        <p>Error line: {$exception->getLine()}</p>
                    ";
                    break;
            }
		} else {
			$log = str_replace('\\', '/', dirname(__DIR__)) . '/logs/http-errors/' . time() . '_http_log.txt';
			ini_set('error_log', $log);
			$message = "-------------------------------";
			$message .= "Uncaught exception: {get_class($exception)}\n";
			$message .= "Message: {$exception->getMessage()}\n";
			$message .= "Stack Trace: {$exception->getTraceAsString()}\n";
			$message .= "Thrown in: {$exception->getFile()}\n";
			$message .= "Error line: {$exception->getLine()}";
			$message .= "-------------------------------";
			error_log($message);

			$code = $exception->getCode();
			$code = $code ? 404 : 500;
			switch ($code) {
				case 404:
					$view = new Views;
					$values = [
						'title' => 'Error 404 | Bingo Framework',
						'stylesheet' => Views::returnUrl(true, 'style') . 'main.css',
						'font' => Views::returnURL(true, 'font') . 'Ubuntu.css',
						'code' => '404',
						'errorspec' => 'Sorry, data not found'
					];
					echo $view->mustacheRender('error', $values);
					break;

				case 500:
					$view = new Views;
					$values = [
						'title' => 'Error 404 | Bingo Framework',
						'stylesheet' => Views::returnUrl(true, 'style') . 'main.css',
						'font' => Views::returnURL(true, 'font') . 'Ubuntu.css',
						'code' => '500',
						'errorspec' => 'Internal Server Error'
					];
					echo $view->mustacheRender('error', $values);
					break;
			}
		}
	}

    /**
     *
     * Logs all console errors to the cli-errors directory
     *
     * @param array $exception The exception to be handled
     *
     */

    public static function cliExceptionHandler($exception)
    {
        $log = str_replace('\\', '/', dirname(__DIR__)) . '/logs/cli-errors/' . time() . '_cmd_log.txt';
        ini_set('error_log', $log);
        $message = "-------------------------------";
        $message .= "Uncaught exception: {get_class($exception)}\n";
        $message .= "Message: {$exception->getMessage()}\n";
        $message .= "Stack Trace: {$exception->getTraceAsString()}\n";
        $message .= "Thrown in: {$exception->getFile()}\n";
        $message .= "Error line: {$exception->getLine()}";
        $message .= "-------------------------------";
        error_log($message);
        echo "An error was detected. Check the logs \n";
    }
}
