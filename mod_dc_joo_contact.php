<?php
	defined('_JEXEC') or die;

	use Joomla\CMS\Helper\ModuleHelper;
	use Joomla\CMS\Uri\Uri;
	use Joomla\CMS\Factory;

	require_once __DIR__ . '/helper.php';

	$doc = Factory::getDocument();

	$loadJquery = (int) $params->get('load_jquery', 0);
	if ($loadJquery === 1) {
		$doc->addScript('https://code.jquery.com/jquery-3.7.1.min.js', [
			'integrity' => 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=',
			'crossorigin' => 'anonymous',
			'defer' => false
		]);
	}

	$cssUrl  = Uri::root(true) . '/modules/mod_dc_joo_contact/assets/css/dc_joo_contact.css';
	$cssPath = JPATH_ROOT . '/modules/mod_dc_joo_contact/assets/css/dc_joo_contact.css';
	$cssVer  = is_file($cssPath) ? (string) filemtime($cssPath) : '1';
	$doc->addStyleSheet($cssUrl . '?v=' . $cssVer);

	$cacheDir = JPATH_ROOT . '/modules/mod_dc_joo_contact/cache';
	$cacheUrl = Uri::root(true) . '/modules/mod_dc_joo_contact/cache';

	if (!is_dir($cacheDir)) {
		if (!mkdir($cacheDir, 0775, true) && !is_dir($cacheDir)) {
			throw new \RuntimeException('Cannot create cache directory: ' . $cacheDir);
		}
	}
	
	if (!is_writable($cacheDir)) {
		throw new \RuntimeException('Cache directory not writable: ' . $cacheDir);
	}

	$srcFiles = [
		JPATH_ROOT . '/modules/mod_dc_joo_contact/assets/js/main.js',
		JPATH_ROOT . '/modules/mod_dc_joo_contact/assets/js/mail.js',
	];

	$bundlePath = $cacheDir . '/dc_joo_contact.bundle.js';
	$bundleUrl  = $cacheUrl . '/dc_joo_contact.bundle.js';

	$needRebuild = !is_file($bundlePath);
	$bundleMtime = $needRebuild ? 0 : (int) filemtime($bundlePath);

	foreach ($srcFiles as $f) {
		if (is_file($f) && (int) filemtime($f) > $bundleMtime) {
			$needRebuild = true;
			break;
		}
	}

	if ($needRebuild) {
		$parts = [];
		$parts[] = "/* DC JOO CONTACT BUNDLE - generated: " . gmdate('c') . " */\n";

		foreach ($srcFiles as $f) {
			if (!is_file($f)) continue;

			$parts[] = "\n/* ===== " . basename($f) . " (mtime: " . gmdate('c', (int) filemtime($f)) . ") ===== */\n";
			$parts[] = file_get_contents($f) ?: '';
			$parts[] = "\n";
		}
		
		$tmp = $bundlePath . '.tmp';
		$result = file_put_contents($tmp, implode('', $parts), LOCK_EX);
		if ($result === false) {
			throw new \RuntimeException('Cannot write cache file: ' . $tmp);
		}

		if (!rename($tmp, $bundlePath)) {
			@unlink($tmp);
			throw new \RuntimeException('Cannot move cache file into place: ' . $bundlePath);
		}
	}

	$bundleVer = is_file($bundlePath) ? (string) filemtime($bundlePath) : '1';
	$doc->addScript($bundleUrl . '?v=' . $bundleVer, ['defer' => true]);

	$data = ModDcJooContactHelper::getData($params);
	require ModuleHelper::getLayoutPath('mod_dc_joo_contact', $params->get('layout', 'default'));
