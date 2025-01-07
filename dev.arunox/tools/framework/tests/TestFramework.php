<?php
	
	class TestFramework
	{
		private static int $testsRun = 0;
		private static int $testsPassed = 0;
		
		/**
		 * Asserts that two values are equal.
		 *
		 * @param mixed $expected The expected value.
		 * @param mixed $actual The actual value.
		 * @param string $message The assertion message.
		 */
		public static function assertEquals($expected, $actual, string $message = ''): void
		{
			self::$testsRun++;
			if ($expected === $actual) {
				self::$testsPassed++;
				echo "[PASS] $message\n";
			} else {
				echo "[FAIL] $message\n";
				echo "  Expected: " . json_encode($expected) . "\n";
				echo "  Got:      " . json_encode($actual) . "\n";
			}
		}
		
		/**
		 * Asserts that a condition is true.
		 *
		 * @param bool $condition The condition to check.
		 * @param string $message The assertion message.
		 */
		public static function assertTrue(bool $condition, string $message = ''): void
		{
			self::$testsRun++;
			if ($condition) {
				self::$testsPassed++;
				echo "[PASS] $message\n";
			} else {
				echo "[FAIL] $message\n";
				echo "  Condition failed.\n";
			}
		}
		
		/**
		 * Asserts that a condition is false.
		 *
		 * @param bool $condition The condition to check.
		 * @param string $message The assertion message.
		 */
		public static function assertFalse(bool $condition, string $message = ''): void
		{
			self::$testsRun++;
			if (!$condition) {
				self::$testsPassed++;
				echo "[PASS] $message\n";
			} else {
				echo "[FAIL] $message\n";
				echo "  Condition unexpectedly passed.\n";
			}
		}
		
		/**
		 * Prints a summary of the tests.
		 */
		public static function summary(): void
		{
			echo "\nTests run: " . self::$testsRun . "\n";
			echo "Tests passed: " . self::$testsPassed . "\n";
			echo "Tests failed: " . (self::$testsRun - self::$testsPassed) . "\n";
		}
	}
