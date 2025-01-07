<?php
	/**
	 * public\index.php
	 *
	 * Entry point for the ARUNOX Framework. Initializes the application and handles incoming requests.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.1.0
	 * @since           1.0.0 File is created
	 */

// Laden der grundlegenden Konfiguration
	require_once __DIR__ . '/../config/init.php';

// Autoloader einbinden
	require_once LIBRARY_DIR . '/ARUNOX/Core/Autoloader.php';
	ARUNOX\Autoloader::register();

// Router initialisieren
	use ARUNOX\Core\Router;
	use ARUNOX\Core\Plugins;
	use ARUNOX\Core\Themes;
	
	use ARUNOX\Core\ErrorHandler;

// Error-Handler aktivieren
	ErrorHandler::register();
	
	$theme = new Themes();
	$theme->render('index.php');
	
	$plugins = Plugins::load();
	
	$router = new Router();

// Beispielroute hinzufügen
	$router->add('/', function () {
		echo json_encode(['message' => 'Welcome to ARUNOX Framework']);
	});

// 404-Route anpassen (Hook)
	use ARUNOX\Core\Hook;
	Hook::add('route_not_found_message', function ($message) {
		return json_encode(['error' => 'Route not found']);
	});

// Anfrage verarbeiten
	Router::dispatch();
