<?php
/**
 * =============================================================================
 * Copyright (c) 2013, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 * =============================================================================
 */
namespace zpt\util;

use \Composer\Autoload\ClassLoader;

/**
 * Map namespaces to file system paths using a specified Composer ClassLoader.
 * This will only search registered PSR-0 and PSR-4 namespaces. Classmap is
 * ignored.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class NamespaceResolver
{

	private $cache = [];

	private $loader;

	/**
	 * Create a new NamespaceResolver for the given Composer ClassLoader.
	 *
	 * @param ClassLoader $loader
	 */
	public function __construct(ClassLoader $loader) {
		$this->loader = $loader;
	}

	/**
	 * Resolve the file system path for the specified namespace. If not found
	 * false is returned.
	 *
	 * @param string $ns
	 */
	public function resolveNamespace($ns) {
		if (isset($this->cache[$ns])) {
			return $this->cache[$ns];
		}

		$logicalPath = strtr($ns, '\\', DIRECTORY_SEPARATOR);

		if ($ns[strlen($ns) - 1] !== '\\') {
			$ns = "$ns\\";
		}

		foreach ($this->loader->getPrefixesPsr4() as $prefix => $dirs) {
			if (0 === strpos($ns, $prefix)) {
				foreach ($dirs as $dir) {
					$path = rtrim($dir, '/') . DIRECTORY_SEPARATOR . substr($logicalPath, strlen($prefix));
					$path = rtrim($path, '/');
					if (is_dir($path)) {
						$this->cache[$ns] = $path;
						return $path;
					}
				}
			}
		}

		foreach ($this->loader->getFallbackDirsPsr4() as $dir) {
			$path = $dir . DIRECTORY_SEPARATOR . $logicalPath;
			if (is_dir($path)) {
				$this->cache[$ns] = $path;
				return $path;
			}
		}

		foreach ($this->loader->getPrefixes() as $prefix => $dirs) {
			if (0 === strpos($ns, $prefix)) {
				foreach ($dirs as $dir) {
					$path = rtrim($dir, '/') . DIRECTORY_SEPARATOR . $logicalPath;
					if (is_dir($path)) {
						$this->cache[$ns] = $path;
						return $path;
					}
				}
			}
		}

		foreach ($this->loader->getFallbackDirs() as $dir) {
			$path = $dir . DIRECTORY_SEPARATOR . $logicalPath;
			if (is_dir($path)) {
				$this->cache[$ns] = $path;
				return $path;
			}
		}

		$this->cache[$ns] = false;
		return false;
	}

}
