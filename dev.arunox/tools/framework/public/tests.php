<?php
	/**
	 * public\tests.php
	 *
	 * A browser-based test runner for the ARUNOX Framework with token-based access control.
	 * Includes tests for the Autoloader and basic framework functionality.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.4.1
	 * @since           1.0.0 File is created
	 */

// Zugriffstoken (ändern für deine Umgebung)
	const TEST_ACCESS_TOKEN = '08154711';

// Zugriffsschutz per Token
	if (!isset($_GET['token']) || $_GET['token'] !== TEST_ACCESS_TOKEN) {
		http_response_code(403);
		echo json_encode(['error' => 'Access denied. Invalid or missing token.']);
		exit;
	}

// Grundlegende Framework-Dateien laden
	require_once __DIR__ . '/../config/init.php';
	require_once LIBRARY_DIR . '/ARUNOX/Autoloader.php';
	require_once __DIR__ . '/../tests/TestFramework.php';
	
	use ARUNOX\Autoloader;
	use ARUNOX\Core\Router;
	
	Autoloader::register();

// Testklasse
	class AutoloaderTest
	{
		public static function logError(string $message): void
		{
			file_put_contents(STORAGE_DIR . '/logs/tests.log', $message . PHP_EOL, FILE_APPEND);
		}
		
		public static function testCoreClass(): void
		{
			try {
				$class = new \ARUNOX\Test\SystemClass();
				TestFramework::assertEquals(
					'System class loaded!',
					$class->sayHello(),
					'Core class should be autoloaded'
				);
			} catch (Throwable $e) {
				self::logError('Core class test failed: ' . $e->getMessage());
			}
		}
		
		public static function testPluginClass(): void
		{
			try {
				$class = new \ARUNOX\Plugins\User();
				TestFramework::assertTrue(
					method_exists($class, 'authenticate'),
					'Plugin class should have an authenticate method'
				);
			} catch (Throwable $e) {
				self::logError('Plugin class test failed: ' . $e->getMessage());
			}
		}
		
		public static function testActivePlugins(): void
		{
			$active = ARUNOX\Core\Plugins::getActive();
			TestFramework::assertTrue(
				in_array('user', $active),
				'User plugin should be active'
			);
			TestFramework::assertFalse(
				in_array('shop', $active),
				'Shop plugin should not be active if its dependency is missing'
			);
		}
		
		public static function testThemeClass(): void
		{
			try {
				$class = new \ARUNOX\Themes\Default\Theme();
				TestFramework::assertEquals(
					'Default Theme',
					$class->getThemeName(),
					'Theme class should return the correct theme name'
				);
			} catch (Throwable $e) {
				self::logError('Theme class test failed: ' . $e->getMessage());
			}
		}
	}
	
	class ApiTest
	{
		public static function testGetUsers(): void
		{
			// Initialisierung der Testumgebung
			$_SERVER['REQUEST_METHOD'] = 'GET';
			$_SERVER['REQUEST_URI'] = '/api/v1/users';
			$_SERVER['HTTP_AUTHORIZATION'] = 'test-token'; // Simuliere einen gültigen Auth-Header
			
			// Anfrage simulieren
			ob_start();
			
			// Stelle sicher, dass Routen korrekt geladen werden
			foreach (ARUNOX\Core\Plugins::getActive() as $plugin) {
				$pluginPath = PLUGINS_DIR . '/' . $plugin;
				ARUNOX\Core\Router::loadApiRoutes($pluginPath);
			}
			ARUNOX\Core\Router::handleRequest(new ARUNOX\Core\Request());
			$response = ob_get_clean();
			
			// Erwartetes Ergebnis
			$expected = json_encode(['users' => []]);
			
			// Testen
			TestFramework::assertEquals($expected, $response, 'GET /api/v1/users should return an empty user list');
		}
		
		
		public static function testCreateUser(): void
		{
			$_SERVER['REQUEST_METHOD'] = 'POST';
			$_SERVER['REQUEST_URI'] = '/api/v1/users';
			file_put_contents('php://input', json_encode(['name' => 'John Doe']));
			ob_start();
			Router::dispatch();
			$response = ob_get_clean();
			
			TestFramework::assertStringContainsString('"User created"', $response, 'POST /api/v1/users should create a user');
		}
		
		public static function testUpdateUser(): void
		{
			$_SERVER['REQUEST_METHOD'] = 'PUT';
			$_SERVER['REQUEST_URI'] = '/api/v1/users/1';
			file_put_contents('php://input', json_encode(['name' => 'Jane Doe']));
			ob_start();
			Router::dispatch();
			$response = ob_get_clean();
			
			TestFramework::assertStringContainsString('"User 1 updated"', $response, 'PUT /api/v1/users/1 should update the user');
		}
		
		public static function testDeleteUser(): void
		{
			$_SERVER['REQUEST_METHOD'] = 'DELETE';
			$_SERVER['REQUEST_URI'] = '/api/v1/users/1';
			ob_start();
			Router::dispatch();
			$response = ob_get_clean();
			
			TestFramework::assertStringContainsString('"User 1 deleted"', $response, 'DELETE /api/v1/users/1 should delete the user');
		}
	}

// Tests ausführen
	echo '<h1>ARUNOX Test Suite</h1>';
	echo '<pre>';
	AutoloaderTest::testCoreClass();
	AutoloaderTest::testPluginClass();
	AutoloaderTest::testActivePlugins();
	AutoloaderTest::testThemeClass();
	ApiTest::testGetUsers();
//	ApiTest::testCreateUser();
//	ApiTest::testUpdateUser();
//	ApiTest::testDeleteUser();
	TestFramework::summary();
	echo '</pre>';