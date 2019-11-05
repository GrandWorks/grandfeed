<?php

require_once(plugin_dir_path( dirname( __FILE__ ) ) .'/includes/class-grand-feed-wrapper.php' );



function response()
{
    $feed_wrapper = new GrandFeedWrapper();
    return json_decode($feed_wrapper->fetch_data(false));
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'grandfeed', '/feeds', array(
      'methods' => 'GET',
      'callback' => 'response',
    ) );
  } );
