<?php
	/**
	 * ARUNOX\Core\Themes
	 *
	 * Manages loading and rendering templates from the active theme.
	 *
	 * @author          Sparky
	 * @copyright       2025 ARUNOX
	 * @version         1.0.0
	 */
	
	namespace ARUNOX\Core;
	
	class Themes
	{
		private string $activeTheme;
		private string $fallbackTheme;
		
		public function __construct()
		{
			$config = include CONFIG_DIR . '/themes.php';
			$this->activeTheme = $config['active_theme'] ?? 'default';
			$this->fallbackTheme = $config['fallback_theme'] ?? 'default';
			
			$this->checkDependencies();
		}
		
		/**
		 * Loads a template file from the active or fallback theme.
		 *
		 * @param string $template The name of the template file (e.g., 'index.php').
		 * @param array $data Data to pass to the template.
		 */
		public function render(string $template, array $data = []): void
		{
			// Suche nach Plugin-Overrides im templates-Ordner der Plugins
			foreach (scandir(PLUGINS_DIR) as $plugin) {
				$pluginTemplatePath = PLUGINS_DIR . "/{$plugin}/templates/{$template}";
				if (file_exists($pluginTemplatePath)) {
					extract($data);
					include $pluginTemplatePath;
					return;
				}
			}
			
			// Lade das Template aus dem aktiven Theme
			$templateFile = THEMES_DIR . "/{$this->activeTheme}/templates/{$template}";
			if (!file_exists($templateFile)) {
				$templateFile = THEMES_DIR . "/{$this->fallbackTheme}/templates/{$template}";
			}
			
			if (file_exists($templateFile)) {
				extract($data);
				include $templateFile;
			} else {
				throw new \Exception("Template not found: {$template}");
			}
		}
		
		public function renderLayout(string $layout, array $data = []): void
		{
			$templateFile = THEMES_DIR . "/{$this->activeTheme}/layouts/{$layout}.php";
			if (!file_exists($templateFile)) {
				$templateFile = THEMES_DIR . "/{$this->fallbackTheme}/layouts/{$layout}.php";
			}
			
			if (file_exists($templateFile)) {
				extract($data);
				include $templateFile;
			} else {
				throw new \Exception("Layout not found: {$layout}");
			}
		}
		
		
		/**
		 * Returns the path to a template part (e.g., 'header.php').
		 *
		 * @param string $part The template part name.
		 * @return string The full path to the template part.
		 */
		public function getTemplatePart(string $part): string
		{
			$partFile = THEMES_DIR . "/{$this->activeTheme}/templates/{$part}.php";
			if (!file_exists($partFile)) {
				$partFile = THEMES_DIR . "/{$this->fallbackTheme}/templates/{$part}.php";
			}
			
			if (!file_exists($partFile)) {
				throw new \Exception("Template part not found: {$part}");
			}
			
			return $partFile;
		}
		
		private function checkDependencies(): void
		{
			$themeConfigPath = THEMES_DIR . "/{$this->activeTheme}/config.php";
			if (!file_exists($themeConfigPath)) {
				throw new \Exception("Config file not found for theme: {$this->activeTheme}");
			}
			
			$config = include $themeConfigPath;
			
			// Lade zentrale Plugin-Aktivierungsdaten
			$pluginStatus = include CONFIG_DIR . '/plugins.php';
			
			foreach ($config['dependencies'] ?? [] as $dependency) {
				if (!($pluginStatus[$dependency] ?? false)) {
					throw new \Exception("Dependency {$dependency} is not active for theme {$this->activeTheme}");
				}
			}
		}
	}
