<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.grandworks.co
 * @since      1.0.0
 *
 * @package    Grand_feed
 * @subpackage Grand_feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Grand_feed
 * @subpackage Grand_feed/admin
 * @author     GrandWorks <hello@grandworks.co>
 */
include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-grand-feed-wrapper.php' );
class Grand_feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	private $grand_feed_wrapper = null;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array($this,'create_plugin_settings_page') ,10,1 );
		$this->grand_feed_wrapper=new GrandFeedWrapper();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Grand_feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Grand_feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/grand_feed-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Grand_feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Grand_feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/grand_feed-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = "GrandFeed";
		$menu_title = "GrandFeed";
		$capability = 'manage_options';
		$slug = 'grand_feed';
		$callback = array($this,'plugin_settings_page_content');
		$icon = 'dashicons-admin-plugins';
		$position = 100;

		// add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
		add_options_page( $page_title, $menu_title, $capability, $slug, $callback );
	}

	public function plugin_settings_page_content()
	{
		if( isset($_POST['updated']) && $_POST['updated'] === 'true' ){
			$this->handle_form();
		}

		if( isset($_POST['fetch-data']) && $_POST['fetch-data']==='true')
		{
			$this->handle_fetch();
		}

		include plugin_dir_path( __FILE__ ) . 'partials/content-admin.php';
	}

	public function handle_form()
	{
		if(!isset($_POST['grandfeed_form']) || ! wp_verify_nonce( $_POST['grandfeed_form'], 'grandfeed' ))
		{
	
			echo '<div class="error">
           		<p>Sorry, your nonce was not correct. Please try again.</p>
        	</div>';

			exit;
		}

		if(isset($_POST['show-instagram']))
		{
			$show_instagram = $_POST['show-instagram'];
		}
		
		if(isset($_POST['show-twitter']))
		{
			$show_twitter = $_POST['show-twitter'];
		}
		
		if(isset($_POST['instagram-client-id']))
		{
			$instagram_client_id = sanitize_text_field($_POST['instagram-client-id']);
		}
		if(isset($_POST['instagram-access-token']))
		{
			$instagram_access_token = sanitize_text_field($_POST['instagram-access-token']);
		}
		
		if(isset($_POST['instagram-feed-count']))
		{
			$instagram_feed_count = sanitize_text_field($_POST['instagram-feed-count']);
		}
		
		if(isset($_POST['twitter-oauth']))
		{
			$twitter_oauth = sanitize_text_field($_POST['twitter-oauth']);
		}
		
		if(isset($_POST['twitter-oauth-secret']))
		{
			$twitter_oauth_secret = sanitize_text_field($_POST['twitter-oauth-secret']);
		}
		
		if(isset($_POST['twitter-consumer-key']))
		{
			$twitter_consumer_key = sanitize_text_field($_POST['twitter-consumer-key']);
		}
		
		if(isset($_POST['twitter-consumer-secret']))
		{
			$twitter_consumer_secret = sanitize_text_field($_POST['twitter-consumer-secret']);
		}
		
		if(isset($_POST['twitter-feed-count']))
		{
			$twitter_feed_count = sanitize_text_field($_POST['twitter-feed-count']);
		}
		
		if(isset($_POST['post-feed-count']))
		{
			$post_feed_count = sanitize_text_field($_POST['post-feed-count']);

		}
		
		update_option( 'grand-feed-show-instagram', $show_instagram );
		update_option( 'grand-feed-show-twitter', $show_twitter );
		
		update_option( 'grand-feed-instagram-client-id', $instagram_client_id );
		update_option( 'grand-feed-instagram-access-token', $instagram_access_token );
		update_option( 'grand-feed-instagram-feed-count', $instagram_feed_count );
		update_option( 'grand-feed-twitter-oauth', $twitter_oauth );
		update_option( 'grand-feed-twitter-oauth-secret', $twitter_oauth_secret );
		update_option( 'grand-feed-twitter-consumer-key', $twitter_consumer_key );
		update_option( 'grand-feed-twitter-consumer-secret', $twitter_consumer_secret );
		update_option( 'grand-feed-twitter-feed-count', $twitter_feed_count );
		update_option( 'grand-feed-post-feed-count', $post_feed_count );

		echo '<div class="updated">
        		<p>Your fields were saved!</p>
    		</div>';
	}

	public function handle_fetch()
	{
		$this->grand_feed_wrapper->refresh_data();
		echo '<div class="updated">
        		<p>Database has been refreshed</p>
    		</div>';
	}


}
