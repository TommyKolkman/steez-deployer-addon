<?php
/**
 * Plugin Name: Steez Deployer Addon
 * Plugin URI: https://steez.nl/
 * Description: Add CLI functions for the Deployer integration.
 * Author: Steez Webdevelopment
 * Version: 1.0.0
 * Author URI: https://steez.nl/
 */

/**
 * SteezDeployerAddon
 *
 * @copyright Copyright © 2021 Steez Webdevelopment. All rights reserved.
 * @author    tommy@steez.nl
 */
class SteezDeployerAddon
{
	
	/**
	 * @var CwApi
	 */
	private $cwApi;
	
	/**
	 * SteezDeployerAddon constructor.
	 */
	public function __construct ()
	{
		$this->bootstrap();
		add_action('cli_init', [$this, 'register_cli_commands']);
		$this->cwApi = false;
	}
	
	/**
	 * Bootstrap all the includes.
	 */
	public function bootstrap ()
	{
		require_once('inc/CwApi.php');
	}
	
	/**
	 * Registers our command when cli get's initialized.
	 * @since  1.0.0
	 * @author Tommy Kolkman
	 */
	public function register_cli_commands ()
	{
		WP_CLI::add_command('steez:reset_cw_file_permissions', [$this, 'reset_cw_file_permissions']);
		WP_CLI::add_command('steez:clear_wp_rocket', [$this, 'clear_wp_rocket']);
		WP_CLI::add_command('steez:restart_php_fpm', [$this, 'restart_php_fpm']);
	}
	
	/**
	 * Clear WP Rocket side wide.
	 */
	public function clear_wp_rocket ()
	{
		// Clear cache.
		if (function_exists('rocket_clean_domain')) {
			if (rocket_clean_domain()) {
				WP_CLI::line('Cleared all WP Rocket cache');
			}
		}
	}
	
	/**
	 * To reset opcache, we have to restart PHP.
	 */
	public function restart_php_fpm ()
	{
		$acces_token_object = $this->get_access_token();
		if (!empty($acces_token_object)) {
			if (defined('WP_CLOUDWAYS_SERVER_ID') && defined('WP_CLOUDWAYS_APP_ID')) {
				if (defined('WP_CLOUDWAYS_PHP_VERSION')) {
					$restart_php = $this->cwApi->call_cloudways_api('POST', '/service/state', $acces_token_object->access_token, [
						'server_id' => WP_CLOUDWAYS_SERVER_ID,
						'service' => sprintf('php%s-fpm', WP_CLOUDWAYS_PHP_VERSION),
						'state' => 'restart'
					]);
					if (!empty($restart_php->service_status->status) && 'running' === $restart_php->service_status->status) {
						WP_CLI::line(sprintf('Restarted service \'php%s-fpm\'', WP_CLOUDWAYS_PHP_VERSION));
					} else {
						WP_CLI::line('Could not restart PHP, unknown error');
					}
				} else {
					WP_CLI::line('Constant WP_CLOUDWAYS_PHP_VERSION not defined!');
				}
			}
		}
	}
	
	/**
	 * Get the access token so we can communicate with the server API.
	 * @return false|mixed
	 */
	private function get_access_token ()
	{
		$acces_token_object = false;
		if (defined('WP_CLOUDWAYS_EMAIL') && defined('WP_CLOUDWAYS_API_KEY')) {
			$this->cwApi = new CwApi(WP_CLOUDWAYS_EMAIL, WP_CLOUDWAYS_API_KEY);
			$acces_token_object = $this->cwApi->call_cloudways_api('POST', '/oauth/access_token', null, [
				'email' => WP_CLOUDWAYS_EMAIL,
				'api_key' => WP_CLOUDWAYS_API_KEY
			]);
			WP_CLI::line('Access token acquired...');
		} else {
			WP_CLI::line('No email or API key defined in the config!');
		}
		
		return $acces_token_object;
	}
	
	/**
	 * Reset file permissions on a Cloudways server. Set WP_CLOUDWAYS_EMAIL, WP_CLOUDWAYS_API_KEY, WP_CLOUDWAYS_SERVER_ID, WP_CLOUDWAYS_APP_ID in the config.
	 * @param null $args
	 * @param null $assoc_args
	 * @when before_wp_load
	 */
	public function reset_cw_file_permissions ($args = null, $assoc_args = null)
	{
		$acces_token_object = $this->get_access_token();
		if (!empty($acces_token_object)) {
			if (defined('WP_CLOUDWAYS_SERVER_ID') && defined('WP_CLOUDWAYS_APP_ID')) {
				WP_CLI::line(sprintf('Resetting permissions for server %s and app %s', WP_CLOUDWAYS_SERVER_ID, WP_CLOUDWAYS_APP_ID));
				if (!empty($this->cwApi)) {
					$reset_permissions = $this->cwApi->call_cloudways_api('POST', '/app/manage/reset_permissions', $acces_token_object->access_token, [
						'server_id' => WP_CLOUDWAYS_SERVER_ID,
						'app_id' => WP_CLOUDWAYS_APP_ID,
						'ownership' => 'master_user'
					]);
					if (!empty($reset_permissions->status)) {
						WP_CLI::line(sprintf('Reset the permissions with operation id %s', $reset_permissions->operation_id));
					}
				}
			}
		}
	}
}

new SteezDeployerAddon();
