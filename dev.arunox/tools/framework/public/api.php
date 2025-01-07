<?php
	
	/**
	 * public\api.php
	 *
	 * Entry point for the ARUNOX Framework REST API.
	 * Supports versioned endpoints and dynamically loads plugin API routes.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.3.3
	 */
	
	use ARUNOX\Core\Router;
	use ARUNOX\Core\Request;
	use ARUNOX\Core\Plugins;
	
	require_once '../config/init.php';

// Plugins laden und API-Routen registrieren
	foreach (Plugins::getActive() as $plugin) {
		$pluginPath = PLUGINS_DIR . '/' . $plugin;
		Router::loadApiRoutes($pluginPath);
	}

// Anfrage weiterleiten
	$request = new Request();
	$requestedUri = $request->getUri();

// Abwärtskompatible Verarbeitung der Anfrage
	$matched = false;
	foreach (API_VERSIONS as $version) {
		if (strpos($requestedUri, "/api/{$version}") === 0) {
			Router::handleRequest($request);
			$matched = true;
			break;
		}
	}

// Fehler 404, wenn keine gültige Version gefunden wurde
	if (!$matched) {
		http_response_code(404);
		echo json_encode(['error' => 'Invalid API endpoint']);
	}
