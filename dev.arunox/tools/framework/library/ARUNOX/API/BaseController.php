<?php
	
	/**
	 * ARUNOX\API\BaseController
	 *
	 * Basisklasse für alle Controller in der API.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\API;
	
	use ARUNOX\Core\Request;
	use ARUNOX\API\Response;
	
	abstract class BaseController
	{
		protected Request $request;
		
		public function __construct(Request $request)
		{
			$this->request = $request;
		}
		
		/**
		 * Gibt eine JSON-Antwort zurück.
		 *
		 * @param array $data
		 * @param int   $status
		 * @return void
		 */
		protected function jsonResponse(array $data, int $status = 200): void
		{
			Response::json($data, $status);
		}
	}
