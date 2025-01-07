<?php
	/**
	 * ARUNOX\Core\Router
	 *
	 * Provides routing functionality for the ARUNOX Framework, supporting GET and POST methods
	 * with middleware integration.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.2.0
	 */
	
	namespace ARUNOX\Core;
	
	class Router
	{
		private static array $routes = [];
		
		public static function loadApiRoutes(string $pluginPath): void
		{
			$apiHookFile = "{$pluginPath}/hooks/api.php";
			
			if (file_exists($apiHookFile)) {
				$routes = include $apiHookFile;
				
				if (is_array($routes)) {
					foreach ($routes as $route => $handler) {
						[$method, $path] = explode(' ', $route, 2); // Trenne Methode und Pfad
						
						foreach (API_VERSIONS as $version) {
							$versionedRoute = "{$method} /api/{$version}{$path}";
							self::$routes[$versionedRoute] = $handler;
						}
					}
				}
			}
		}
		
		public static function handleRequest(Request $request): void
		{
			$uri = $request->getUri();
			$method = $request->getMethod();
			
			foreach (self::$routes as $route => $handler) {
				[$routeMethod, $routeUri] = explode(' ', $route, 2);
				
				if ($method === $routeMethod && $uri === $routeUri) {
					call_user_func($handler, $request);
					return;
				}
			}
			
			// 404 Not Found
			http_response_code(404);
			echo json_encode(['error' => 'Route not found']);
		}
		
		public static function get(string $path, $callback, ?array $middleware = null): void
		{
			self::$routes['GET'][$path] = [$callback, $middleware];
		}
		
		public static function post(string $path, $callback, ?array $middleware = null): void
		{
			self::$routes['POST'][$path] = [$callback, $middleware];
		}
		
		public static function dispatch(): void
		{
			$method = $_SERVER['REQUEST_METHOD'] ?? '';
			$path = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
			
			$route = self::$routes[$method][$path] ?? null;
			
			if (!$route) {
				http_response_code(404);
				echo json_encode(['error' => 'Route not found']);
				return;
			}
			
			[$callback, $middleware] = $route;
			
			if ($middleware) {
				foreach ($middleware as $handler) {
					if (call_user_func([$handler, 'handle']) === false) {
						return;
					}
				}
			}
			
			if (is_callable($callback)) {
				echo call_user_func($callback);
			} elseif (is_array($callback)) {
				[$class, $method] = $callback;
				echo call_user_func([new $class, $method]);
			}
		}
	}
	
	/**
	 * Middleware execution and dynamic route dispatching is fully supported.
	 */
