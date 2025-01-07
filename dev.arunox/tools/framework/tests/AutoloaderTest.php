<?php
	
	require_once __DIR__ . '/../config/constants.php';
	require_once __DIR__ . '/../config/config.php';
	require_once __DIR__ . '/../config/init.php';
	require_once LIBRARY_DIR . '/ARUNOX/Core/Autoloader.php';
	
	use ARUNOX\Autoloader;
	
	Autoloader::register();
	
	class TestClass
	{
		public function sayHello(): string
		{
			return 'Autoloader works!';
		}
	}

// Testfall: Klasse laden und Funktion aufrufen
	try {
		$testClass = new \TestClass();
		echo $testClass->sayHello();
	} catch (Throwable $e) {
		echo 'Autoloader Test Failed: ' . $e->getMessage();
	}
