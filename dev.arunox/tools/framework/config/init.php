<?php
	/**
	 * config\init.php
	 *
	 * Initializes the ARUNOX Framework, loading configuration files and setting up core components.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 * @since           1.0.0 File is created
	 */
	
// Laden der grundlegenden Konfiguration
	require_once __DIR__ . '/constants.php';
	require_once __DIR__ . '/config.php';
	require_once dirname( __DIR__, 3 ) . '/core/functions/de.arunox.tools.show.php';
	
	if( DEBUG_MODE ) {
		/* ###  SET ERROR REPORTING  ##########################################	# */
		error_reporting(E_ERROR | E_WARNING | E_PARSE | ~E_NOTICE );
		ini_set("display_errors", "On" );
	}

// Autoloader einbinden
	require_once LIBRARY_DIR . '/ARUNOX/Autoloader.php';
	ARUNOX\Autoloader::register();

// Initialisierung wichtiger Verzeichnisse
	foreach (ESSENTIAL_DIRS as $dir) {
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
	}

// Fehlerbehandlung und Logging
	if (!file_exists(STORAGE_DIR . '/logs')) {
		mkdir(STORAGE_DIR . '/logs', 0755, true);
	}
	
	function logError($message) {
		file_put_contents(STORAGE_DIR . '/logs/error.log', $message . PHP_EOL, FILE_APPEND);
	}
