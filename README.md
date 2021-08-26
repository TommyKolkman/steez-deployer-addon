# steez-deployer-addon
An addon for my Deployer project, also with an integration for Cloudways servers.

This project helps me deploy my WordPress instances to Cloudways servers.

# Requirements

* Cloudways server
* Cloudways API key
* Deployer.org integration

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
