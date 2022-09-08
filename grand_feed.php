<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.grandworks.co
 * @since             1.0.0
 * @package           Grand_feed
 *
 * @wordpress-plugin
 * Plugin Name:       GrandFeed
 * Plugin URI:        https://www.grandworks.co
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            GrandWorks
 * Author URI:        https://www.grandworks.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       grand_feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GRAND_FEED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-grand_feed-activator.php
 */
function activate_grand_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-grand_feed-activator.php';
	Grand_feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-grand_feed-deactivator.php
 */
function deactivate_grand_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-grand_feed-deactivator.php';
	Grand_feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_grand_feed' );
register_deactivation_hook( __FILE__, 'deactivate_grand_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-grand_feed.php';

require plugin_dir_path( __FILE__ ) . 'includes/class-grand-feed-database.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-grand-feed-wrapper.php';

function fetch_feeds($type_array=true)
{
	$feed_wrapper = new GrandFeedWrapper();
	return $feed_wrapper->fetch_data($type_array);
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'grandfeed', '/feeds', array(
	  'methods' => 'GET',
	  'callback' => 'fetch_feeds',
) );
});

function run_grand_feed() {

	$plugin = new Grand_feed();
	$plugin->run();
	
}
run_grand_feed();

