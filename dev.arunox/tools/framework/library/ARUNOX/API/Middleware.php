<?php
	
	/**
	 * ARUNOX\API\Middleware
	 *
	 * Middleware für API-spezifische Aufgaben.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\API;
	
	class Middleware
	{
		public static function handleCors(): void
		{
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
			header("Access-Control-Allow-Headers: Content-Type, Authorization");
			if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
				http_response_code(200);
				exit;
			}
		}
		
		public static function authenticate(): void
		{
			$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
			if (!$authHeader || !self::isValidToken($authHeader)) {
				Response::json(['error' => 'Unauthorized'], 401);
			}
		}
		
		private static function isValidToken(string $token): bool
		{
			// Token-Validierungslogik
			return $token === 'test-token'; // Beispiel
		}
		
		public static function rateLimit(): void
		{
			// Rate-Limiting-Logik
		}
	}
