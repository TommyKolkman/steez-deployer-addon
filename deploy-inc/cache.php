<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @author      Tommy Kolkman <tommy@steez.nl>
 * @copyright   Copyright (c) 2020 Steez (https://steez.nl)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

use function Deployer\run;
use function Deployer\task;

task('steez:clear-cache', function () {
	run("cd {{release_path}}");
	// Clear opcache by restarting PHP
	run("/usr/local/bin/wp --path='{{release_path}}' steez:restart_php_fpm");
	// Clear all redis data
	run("redis-cli flushall");
	// Update the dropin if necessary
	run("/usr/local/bin/wp --path='{{release_path}}' redis update-dropin");
	// Flush all WP cache, transients and such
	run("/usr/local/bin/wp --path='{{release_path}}' cache flush");
	// Clear WP Rocket
	run("/usr/local/bin/wp --path='{{release_path}}' steez:clear_wp_rocket");
});
