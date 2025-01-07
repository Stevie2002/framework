<?php
	/**
	 * ARUNOX\Core\Middleware\AuthMiddleware
	 *
	 * Middleware to enforce authentication on protected routes.
	 */
	
	namespace ARUNOX\Core\Middleware;
	
	use ARUNOX\Core\Auth;
	
	class AuthMiddleware
	{
		public static function handle(): bool
		{
			$headers = getallheaders();
			$token = $headers['Authorization'] ?? '';
			
			if (!$token || !Auth::validateToken(str_replace('Bearer ', '', $token))) {
				http_response_code(401);
				echo json_encode(['error' => 'Unauthorized']);
				return false;
			}
			
			return true;
		}
	}
