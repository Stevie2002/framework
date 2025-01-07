<?php
	/**
	 * ARUNOX\Autoloader
	 *
	 * Dynamically loads classes based on namespace and directory mapping.
	 * Searches in core, plugin, and theme directories for matching class files.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.4.0
	 * @since           1.0.0 File is created
	 */
	
	namespace ARUNOX;
	
	class Autoloader
	{
		/**
		 * @var array $locationMap List of base directories to search for class files.
		 */
		private static array $locationMap = [];
		
		/**
		 * Registers the autoloader function and sets up initial locations.
		 */
		public static function register(): void
		{
			spl_autoload_register([__CLASS__, 'autoload']);
			
			// Add core, plugins, and themes to the search map
			self::addLocation(ROOT_DIR);
			self::addLocation(PLUGINS_DIR . "/*");
			self::addLocation(THEMES_DIR . "/*");
		}
		
		/**
		 * Adds a base directory to the list of locations to search for class files.
		 *
		 * @param string $baseDir The base directory to add.
		 */
		public static function addLocation(string $baseDir): void
		{
			self::$locationMap[] = rtrim($baseDir, '/');
		}
		
		/**
		 * Autoloads a class by searching in the defined locations.
		 *
		 * @param string $class The fully-qualified class name.
		 */
		public static function autoload(string $class): void
		{
			$class = explode('\\', $class);
			$name = end($class); // The class name itself
			$class = implode('/', $class); // The full class path
			
			// Possible file structures for class files
			$files = [
				"/library/{$class}.php",
				"/library/{$class}/{$name}.php",
			];
			
			// Search through the mapped locations
			foreach (self::$locationMap as $baseDir) {
				foreach ($files as $file) {
					$file = current(glob("{$baseDir}/{$file}"));
					
					if (file_exists($file)) {
						require_once $file;
						return;
					}
				}
			}
			
			// Log error if no file is found
			self::logError("Class file not found: $class");
		}
		
		/**
		 * Logs errors encountered during the autoload process.
		 *
		 * @param string $message The error message.
		 */
		private static function logError(string $message): void
		{
			file_put_contents(STORAGE_DIR . '/logs/autoloader.log', $message . PHP_EOL, FILE_APPEND);
		}
	}
