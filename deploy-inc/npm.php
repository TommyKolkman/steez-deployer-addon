<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

set('bin/npm', function () {
	return run('which npm');
});


task('npm:install', function () {
	desc('Install npm packages');
	if (has('previous_release')) {
		if (test('[ -d {{previous_release}}/node_modules ]')) {
			run('cp -R {{previous_release}}/node_modules {{release_path}}');
		}
	}
	run("cd {{release_path}}/wp-content/themes/{{theme_folder_path}} && {{bin/npm}} install");
});
task('npm:production', function () {
	desc('Build all the assets, remove node_modules and the assets afterwards');
	run("cd {{release_path}}/wp-content/themes/{{theme_folder_path}} && {{bin/npm}} run production");
	run("rm -rf {{release_path}}/wp-content/themes/{{theme_folder_path}}/node_modules");
	run("rm -rf {{release_path}}/wp-content/themes/{{theme_folder_path}}/assets");
});
