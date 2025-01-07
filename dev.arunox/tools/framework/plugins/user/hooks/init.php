<?php
	/**
	 * plugins\User\hooks\init.php
	 *
	 * Hook definitions for the User plugin.
	 * Adds authentication-related hooks to the ARUNOX Framework.
	 *
	 * @author          Sparky <sparky@arunox.de>
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 * @since           1.0.0 File is created
	 */
	
	use ARUNOX\Core\Hook;
	use ARUNOX\Plugins\User;

// Beispiel: Authentifizierungshook
	Hook::add('before_route_dispatch', function ($route) {
		if ($route === '/user/login') {
			echo 'User login route triggered!';
		}
	});
