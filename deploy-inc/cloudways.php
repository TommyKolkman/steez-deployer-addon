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

/**
 * Reset the file permissions on Cloudways.
 * @desc Don't forget to set the config values first!
 */

use function Deployer\run;
use function Deployer\task;

task('cloudways:reset-file-permissions', function () {
	run("cd {{release_path}}");
	run("/usr/local/bin/wp --path='{{release_path}}' plugin activate steez-deployer-addon");
	run("/usr/local/bin/wp --path='{{release_path}}' steez:reset_cw_file_permissions");
});
