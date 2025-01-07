<?php
	/**
	 * ARUNOX\Core\Plugins
	 *
	 * Manages loading, activating, and handling plugins in the ARUNOX Framework.
	 * Uses centralized activation configuration and plugin-specific dependencies.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.3.0
	 */
	
	namespace ARUNOX\Core;
	
	class Plugins
	{
		private static array $activePlugins = [];
		private static array $allPlugins = [];
		
		/**
		 * Loads the plugin configuration and activation status.
		 */
		public static function config(): void
		{
			$globalConfig = CONFIG_DIR . '/plugins.php';
			
			if( file_exists( $globalConfig ) ) {
				$activePlugins	= include $globalConfig;
				$pluginsDirs	= glob(PLUGINS_DIR . "/*");
				
				foreach( $pluginsDirs as $pluginDir ) {
					$pluginName 	  = basename( $pluginDir );
					$pluginConfigPath = "{$pluginDir}/config.php";
					
					if( file_exists( $pluginConfigPath ) ) {
						static::$allPlugins[ $pluginName ] = [
							"active"		=> $activePlugins[ $pluginName ] ?? FALSE,
							"dependencies"	=> $pluginConfig["dependencies"] ?? [],
						];
					}
				}
			}
		}
		
		/**
		 * Loads and activates all plugins.
		 */
		public static function load( bool $force = FALSE ): void
		{
			if( empty( static::$allPlugins ) || $force ) self::config();
			
			foreach( static::$allPlugins as $plugin => $config ) {
				if( $config["active"] ) {
					static::activate( $plugin, $config["dependencies"] );
				}
			}
		}
		
		/**
		 * Activates a plugin and checks its dependencies.
		 *
		 * @param string $plugin The plugin name.
		 * @param array $dependencies List of plugin dependencies.
		 */
		public static function activate( string $plugin, array $dependencies ): void
		{
			foreach( $dependencies as $dependency ) {
				if( !( static::$allPlugins[ $dependency ]["active"] ?? FALSE ) ) {
					throw new \Exception("Missing dependency: {$dependency} for plugin {$plugin}");
				}
			}
			
			$pluginPath = PLUGINS_DIR . "/{$plugin}/hooks/init.php";
			if( file_exists( $pluginPath ) ) {
				include_once $pluginPath;
				static::$activePlugins[] = $plugin;
			}
		}
		
		/**
		 * Returns a list of active plugins.
		 *
		 * @return array List of active plugins.
		 */
		public static function getActive( bool $force = FALSE ): array
		{
			if( empty( static::$activePlugins ) || $force ) self::load( $force );
			
			return static::$activePlugins;
		}
	}
