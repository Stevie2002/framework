<?php
	
	require_once __DIR__ . '/../config/constants.php';
	require_once __DIR__ . '/../config/config.php';
	require_once __DIR__ . '/../config/init.php';
	require_once LIBRARY_DIR . '/ARUNOX/Core/Autoloader.php';
	require_once __DIR__ . '/TestFramework.php';
	
	use ARUNOX\Core\Router;
	
	ARUNOX\Autoloader::register();

// Testklasse
	class RouterTest
	{
		public static function testCanAddAndDispatchRoute(): void
		{
			$router = new Router();
			
			// Testroute hinzufügen
			$router->add('/test', function () {
				echo 'Test route works!';
			});
			
			ob_start();
			$router->dispatch('/test');
			$output = ob_get_clean();
			
			TestFramework::assertEquals('Test route works!', $output, 'Route dispatch should output correct response');
		}
		
		public static function testRouteNotFound(): void
		{
			$router = new Router();
			
			ob_start();
			$router->dispatch('/unknown');
			$output = ob_get_clean();
			
			TestFramework::assertStringContainsString('404 Not Found', $output, 'Route not found should output 404 message');
		}
	}

// Tests ausführen
	RouterTest::testCanAddAndDispatchRoute();
	RouterTest::testRouteNotFound();
	TestFramework::summary();
