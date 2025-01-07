<?php
	
	/**
	 * ARUNOX\Core\Request
	 *
	 * Klasse zur Verarbeitung und Analyse von HTTP-Anfragen.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\Core;
	
	class Request
	{
		private array $queryParams;
		private array $bodyParams;
		private array $headers;
		private string $method;
		private string $uri;
		
		public function __construct()
		{
			$this->queryParams = $_GET ?? [];
			$this->bodyParams = $this->parseBody();
			$this->headers = getallheaders() ?: [];
			$this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
			$this->uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?'); // URI ohne Query-String
		}
		
		/**
		 * Gibt die Query-Parameter zurück.
		 *
		 * @return array
		 */
		public function getQueryParams(): array
		{
			return $this->queryParams;
		}
		
		/**
		 * Gibt die Body-Parameter zurück (JSON).
		 *
		 * @return array
		 */
		public function getBodyParams(): array
		{
			return $this->bodyParams;
		}
		
		/**
		 * Gibt die Header der Anfrage zurück.
		 *
		 * @return array
		 */
		public function getHeaders(): array
		{
			return $this->headers;
		}
		
		/**
		 * Gibt die HTTP-Methode zurück.
		 *
		 * @return string
		 */
		public function getMethod(): string
		{
			return $this->method;
		}
		
		/**
		 * Gibt die angeforderte URI ohne Query-String zurück.
		 *
		 * @return string
		 */
		public function getUri(): string
		{
			return $this->uri;
		}
		
		/**
		 * Parst den Anfrage-Body.
		 *
		 * @return array
		 */
		private function parseBody(): array
		{
			$input = file_get_contents('php://input');
			$decoded = json_decode($input, true);
			return is_array($decoded) ? $decoded : [];
		}
	}
