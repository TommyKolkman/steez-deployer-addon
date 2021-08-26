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
 * Building assets
 * @todo This is possible, but very slow!
 */
//desc( 'Build the assets on the server' );
//task( 'build:assets:dev', 'cd {{ release_path }}/wp-content/themes/{{ theme_folder_path }} && npm install --loglevel info && npm run development --loglevel info && cd {{ release_path }}');
use function Deployer\desc;
use function Deployer\has;
use function Deployer\run;
use function Deployer\set;
use function Deployer\task;
use function Deployer\test;

set('bin/npm', function () {
	return run('which npm');
});

desc('Install npm packages');
task('npm:install_custom', function () {
	if (has('previous_release')) {
		if (test('[ -d {{previous_release}}/wp-content/themes/{{ theme_folder_path }}/node_modules ]')) {
			run('cp -R {{previous_release}}/wp-content/themes/{{ theme_folder_path }}/node_modules {{release_path}}');
		}
	}
	run('node -v');
	run('npm -v');
	run("cd {{release_path}}/wp-content/themes/{{ theme_folder_path }} && {{bin/npm}} install");
});

task('npm:run_development', function () {
	run("cd {{release_path}}/wp-content/themes/{{ theme_folder_path }} && {{bin/npm}} run development");
});

task('npm:run_production', function () {
	run("cd {{release_path}}/wp-content/themes/{{ theme_folder_path }} && {{bin/npm}} run production");
});
