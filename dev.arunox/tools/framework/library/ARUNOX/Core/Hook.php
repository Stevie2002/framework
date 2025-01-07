<?php
	/**
	 * ARUNOX\Core\Hook
	 *
	 * Provides a unified hook mechanism for managing actions and filters within the ARUNOX Framework.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 * @since           1.0.0 File is created
	 */
	
	namespace ARUNOX\Core;
	
	class Hook
	{
		/**
		 * @var array $hooks Registered hooks (actions and filters).
		 */
		private static array $hooks = [];
		
		/**
		 * Adds a hook (action or filter).
		 *
		 * @param string $name The name of the hook.
		 * @param callable $callback The callback function to attach.
		 * @param int $priority The priority of the hook (lower runs first).
		 */
		public static function add(string $name, callable $callback, int $priority = 10): void
		{
			self::$hooks[$name][$priority][] = $callback;
			ksort(self::$hooks[$name]);
		}
		
		/**
		 * Removes a hook (action or filter).
		 *
		 * @param string $name The name of the hook.
		 * @param callable $callback The callback function to remove.
		 */
		public static function remove(string $name, callable $callback): void
		{
			if (isset(self::$hooks[$name])) {
				foreach (self::$hooks[$name] as $priority => $callbacks) {
					foreach ($callbacks as $key => $registeredCallback) {
						if ($registeredCallback === $callback) {
							unset(self::$hooks[$name][$priority][$key]);
						}
					}
					
					if (empty(self::$hooks[$name][$priority])) {
						unset(self::$hooks[$name][$priority]);
					}
				}
			}
		}
		
		/**
		 * Executes all actions attached to a hook.
		 *
		 * @param string $name The name of the hook.
		 * @param mixed ...$args Arguments to pass to the callbacks.
		 */
		public static function apply(string $name, ...$args): void
		{
			if (!isset(self::$hooks[$name])) {
				return;
			}
			
			foreach (self::$hooks[$name] as $priority => $callbacks) {
				foreach ($callbacks as $callback) {
					call_user_func_array($callback, $args);
				}
			}
		}
		
		/**
		 * Applies all filters attached to a hook and returns the filtered value.
		 *
		 * @param string $name The name of the hook.
		 * @param mixed $value The initial value to filter.
		 * @param mixed ...$args Additional arguments to pass to the callbacks.
		 * @return mixed The filtered value.
		 */
		public static function filter(string $name, $value, ...$args)
		{
			if (!isset(self::$hooks[$name])) {
				return $value;
			}
			
			foreach (self::$hooks[$name] as $priority => $callbacks) {
				foreach ($callbacks as $callback) {
					$value = call_user_func_array($callback, array_merge([$value], $args));
				}
			}
			
			return $value;
		}
	}
