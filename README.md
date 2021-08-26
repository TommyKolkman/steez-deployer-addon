# steez-deployer-addon
An addon for my Deployer project, also with an integration for Cloudways servers.

This project helps me deploy my WordPress instances to Cloudways servers.

# Requirements

* Cloudways server
* Cloudways API key
* Deployer.org integration

## Extras

* Add 'redis-cache' and it will be cleared.
* Add 'wp-rocket' and it will be cleared.
* File permissions in Cloudways will be reset.

Everything can be used in WP CLI as well:

```
wp steez:restart_php_fpm
wp steez:clear_wp_rocket
wp steez:reset_cw_file_permissions
```

# Instructions

Add the following constants to your config:

```
define('WP_CLOUDWAYS_EMAIL', 'x');
define('WP_CLOUDWAYS_API_KEY', 'x');
define('WP_CLOUDWAYS_SERVER_ID', 'x');
define('WP_CLOUDWAYS_APP_ID', 'x');
define('WP_CLOUDWAYS_PHP_VERSION', '7.4');
```

# Tip

Add it as a submodule in wp-content/plugins, works nicely, Deployer will clone it.
