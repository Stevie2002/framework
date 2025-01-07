<?php
	
	/**
	 * ARUNOX\API\Response
	 *
	 * API-Antwort-Handling.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\API;
	
	class Response
	{
		public static function json(array $data, int $status = 200): void
		{
			http_response_code($status);
			header('Content-Type: application/json');
			echo json_encode($data);
			exit;
		}
	}
