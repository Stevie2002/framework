<?php
	
	/**
	 * config\config.php
	 *
	 * Provides essential configurations for the ARUNOX Framework.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.2.1
	 */

// Database configuration
	const DB_HOST = 'localhost';
	const DB_NAME = 'arunox';
	const DB_USER = 'root';
	const DB_PASS = 'password';
	const DB_CHARSET = 'utf8mb4';

// Framework settings
	const DEFAULT_LANGUAGE = 'en';
	const SUPPORTED_LANGUAGES = ['en', 'de', 'fr']; // Add other languages as needed

// Security settings
	const TOKEN_SECRET = 'your-secure-token-secret'; // Replace with a secure, random key
	const TOKEN_EXPIRY = 3600; // Token expiry time in seconds

// Debugging and Error Logging
	const DEBUG_MODE = true; // Set to false in production
	const LOG_ERRORS = true; // Enable or disable error logging

// API configuration
	const API_VERSIONS = ['v1', 'v2']; // Supported API versions
	const API_DEFAULT_VERSION = 'v1'; // Default API version for backward compatibility
