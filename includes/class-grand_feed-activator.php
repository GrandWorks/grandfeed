<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.grandworks.co
 * @since      1.0.0
 *
 * @package    Grand_feed
 * @subpackage Grand_feed/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Grand_feed
 * @subpackage Grand_feed/includes
 * @author     GrandWorks <hello@grandworks.co>
 */
include_once(plugin_dir_path( __FILE__ ) . 'class-grand-feed-database.php');
class Grand_feed_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		$grand_feed = GrandFeedDatabase::instantiate();
		$grand_feed->initiate_table();

	}

}
