<?php
	/**
	 * ARUNOX\Core\Controller
	 *
	 * Base controller with middleware support for RESTful requests in ARUNOX Framework.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.1.0
	 * @since           1.0.0 File is created
	 */
	
	namespace ARUNOX\Core;
	
	class Controller
	{
		protected array $middleware = [];
		
		/**
		 * Returns the middleware stack for the controller.
		 *
		 * @return array The middleware stack.
		 */
		public function getMiddleware(): array
		{
			return $this->middleware;
		}
		
		/**
		 * Adds middleware to the controller.
		 *
		 * @param callable $middleware The middleware function to add.
		 */
		protected function addMiddleware(callable $middleware): void
		{
			$this->middleware[] = $middleware;
		}
	}
