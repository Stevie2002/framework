<?php
	/**
	 * config\constants.php
	 *
	 * Defines global constants for directory paths and configurations.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 * @since           1.0.0 File is created
	 */

// Define the root directory
	const ROOT_DIR = __DIR__ . '/..';

// Define paths for core directories
	const LIBRARY_DIR = ROOT_DIR . '/library';
	const PLUGINS_DIR = ROOT_DIR . '/plugins';
	const THEMES_DIR = ROOT_DIR . '/themes';
	const CONFIG_DIR = ROOT_DIR . '/config';

// Define paths for storage
	const STORAGE_DIR = ROOT_DIR . '/storage';
	const CACHE_DIR = STORAGE_DIR . '/cache';
	const UPLOADS_DIR = STORAGE_DIR . '/uploads';

// Define essential directories to initialize
	const ESSENTIAL_DIRS = [
		STORAGE_DIR,
		CACHE_DIR,
		UPLOADS_DIR,
	];
