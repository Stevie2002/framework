<?php
	/**
	 * plugins\user\hooks\api.php
	 *
	 * Registers API routes for the User plugin.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.1.0
	 */
	
	use ARUNOX\API\Response;
	use ARUNOX\API\Middleware;
	
	Middleware::handleCors();
	
	return [
		'GET /users' => function ($request) {
			Middleware::authenticate();
			Response::json(['users' => ['John Doe', 'Jane Doe']]);
		},
		'POST /users' => function ($request) {
			Middleware::authenticate();
			$data = $request->getBodyParams();
			Response::json(['message' => 'User created', 'data' => $data]);
		},
		'DELETE /users/{id}' => function ($request, $id) {
			Middleware::authenticate();
			Response::json(['message' => "User {$id} deleted"]);
		},
	];
