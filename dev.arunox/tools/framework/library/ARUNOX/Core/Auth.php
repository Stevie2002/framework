<?php
	/**
	 * ARUNOX\Core\Auth
	 *
	 * Provides JWT-based authentication for the REST API.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\Core;
	
	class Auth
	{
		public static function generateToken(array $payload, int $expiry = TOKEN_EXPIRY): string
		{
			$payload['iat'] = time();
			$payload['exp'] = time() + $expiry;
			
			return base64_encode(json_encode($payload)) . '.' . hash_hmac('sha256', json_encode($payload), TOKEN_SECRET);
		}
		
		public static function validateToken(string $token): bool
		{
			[$payload, $signature] = explode('.', $token);
			$expectedSignature = hash_hmac('sha256', $payload, TOKEN_SECRET);
			
			if ($signature !== $expectedSignature) {
				return false;
			}
			
			$data = json_decode(base64_decode($payload), true);
			return isset($data['exp']) && $data['exp'] > time();
		}
	}
