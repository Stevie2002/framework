<?php
	/**
	 * ARUNOX\Core\ErrorHandler
	 *
	 * Provides centralized error handling and logging for the ARUNOX Framework.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\Core;
	
	class ErrorHandler
	{
		public static function register(): void
		{
			if (DEBUG_MODE) {
				ini_set('display_errors', '1');
				ini_set('display_startup_errors', '1');
				error_reporting(E_ALL);
			} else {
				ini_set('display_errors', '0');
				error_reporting(0);
			}
			
			set_error_handler([__CLASS__, 'handleError']);
			set_exception_handler([__CLASS__, 'handleException']);
			register_shutdown_function([__CLASS__, 'handleShutdown']);
		}
		
		public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
		{
			$message = "[ERROR] {$errstr} in {$errfile} on line {$errline}";
			self::log($message);
			if (ini_get('display_errors')) {
				echo $message . PHP_EOL;
			}
		}
		
		public static function handleException(\Throwable $exception): void
		{
			$message = "[EXCEPTION] {$exception->getMessage()} in {$exception->getFile()} on line {$exception->getLine()}";
			self::log($message);
			if (ini_get('display_errors')) {
				echo $message . PHP_EOL;
			}
		}
		
		public static function handleShutdown(): void
		{
			$error = error_get_last();
			if ($error !== null) {
				$message = "[SHUTDOWN] {$error['message']} in {$error['file']} on line {$error['line']}";
				self::log($message);
				if (ini_get('display_errors')) {
					echo $message . PHP_EOL;
				}
			}
		}
		
		private static function log(string $message): void
		{
			if (LOG_ERRORS) {
				file_put_contents(STORAGE_DIR . '/logs/error.log', $message . PHP_EOL, FILE_APPEND);
			}
		}
	}
