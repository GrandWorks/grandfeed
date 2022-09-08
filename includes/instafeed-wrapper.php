<?php
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
it_initialize();
$options = get_option('it_settings');
$instagram_client_id = $options['instagram_client_id'];
$instagram_access_token = $options['instagram_access_token'];
$instagram_feed_count = $options['insta_count'];
$twitter_oauth = $options['twitter_oauth'];
$twitter_oauth_secret = $options['twitter_oauth_secret'];
$twitter_consumer_key = $options['twitter_consumer_key'];
$twitter_consumer_secret = $options['twitter_consumer_secret'];
$twitter_feed_count = $options['tweet_count'];
$post_count = $options['post_count'];
$show_instagram_feed = $options['show_instagram_feeds'];
$show_tweet = $options['show_tweets'];
$twitter = "";
$instafeed = "";

if($show_instagram_feed==1)
{
    $instafeed = new Instafeed($instagram_client_id,$instagram_access_token,$instagram_feed_count);
}
if($show_tweet==1)
{
    $twitter = new Twitterfeed($twitter_oauth,$twitter_oauth_secret,$twitter_consumer_key,$twitter_consumer_secret,$twitter_feed_count);
}

function it_compile_feeds()
{
    global $instafeed;
    global $twitter;
    global $show_instagram_feed;
    global $show_tweet;
    global $twitter_feed_count;
    global $post_count;
    $compiled_array = array();
    $instafeeds = $instafeed->get_feed_new_api();
    $twitterfeeds = json_decode($twitter->get_tweets());
    // $twitter_url = "http://twitter.com/".$twitterfeeds[0]->user->screen_name."/status/";
    
    $instagram_array=array();
    if($show_instagram_feed==1)
    {
        $insta_temp=array();
        
        foreach ($instafeeds as $key => $value) {
            $image_url = "";
            if($value->media_type == "VIDEO")
            {
                $image_url = $value->thumbnail_url;
            }
            else
            {
                $image_url = $value->media_url;
            }
        //    $image_url = $value->images->standard_resolution->url;
           $feed_url = $value->permalink;
           $insta_temp = array(
               "image_url" => $image_url,
               "feed_url" => $feed_url
           );
           array_push($instagram_array,$insta_temp);
        }
    }
    
    $twitter_array=array();
    if($show_tweet==1)
    {
        $twitter_temp=array();
        $counter=0;
    foreach ($twitterfeeds as $key => $value) {
        if($counter==$twitter_feed_count) {break;}
        $tweet_date = $value->created_at;
        $tweet_text = $value->text;
        $tweet_url = $twitter_url. $value->id_str;
        $twitter_temp = array(
            "tweet_date" => $tweet_date,
            "tweet_text" => $tweet_text,
            "tweet_url" => $tweet_url
        );
        array_push($twitter_array,$twitter_temp);
        $counter++;
     }
    }
    
    $pots_feeds = new WP_Query(array(
        "post_type" => "",
        "posts_per_page" => $post_count,
        "post_status" => "publish"
    ));

    $posts_array=array();
    
    if($pots_feeds->have_posts())
    {
        while($pots_feeds->have_posts())
        {
            $pots_feeds->the_post();
            $posts_temp_array=array(
                "post_url" => get_permalink(),
                "featured_image" => get_the_post_thumbnail_url(null, "grand-thumbnail"),
                "title" => get_the_title(),
                "excerpt" => get_the_excerpt(),
                "date" => get_the_date()
            );

            array_push($posts_array,$posts_temp_array);
        }
    }
    wp_reset_postdata();

     $compiled_array = array(
         "insta_feeds" => $instagram_array,
         "tweets" => $twitter_array,
         "posts" => $posts_array
     );
     
    return $compiled_array;
}

// function it_initialize() 
// {
//     global $wpdb;

//     $table_name = $wpdb->prefix . "insta_tweet";
//     $charset_collate = $wpdb->get_charset_collate();

//     $sql = "CREATE TABLE IF NOT EXISTS $table_name (
//     id mediumint (255) NOT NULL AUTO_INCREMENT,
//     json_data TEXT NOT NULL,
//     PRIMARY KEY (id)
//     ) $charset_collate;";

//     $result = dbDelta($sql);
// }

function it_refresh_json_data()
{
    global $wpdb;
    $feeds = it_compile_feeds();
    
    $table_name = $wpdb->prefix . "insta_tweet";
    $result = $wpdb->insert(
        $table_name,
        array(
            'json_data' => json_encode($feeds),
        )
    );
}

function it_get_insta_tweet($type_array=false)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "insta_tweet";
        $result = $wpdb->get_row( "select json_data from $table_name order by id DESC limit 1" );
    if($type_array)
    {
        return json_decode($result->json_data);
    }

    return $result->json_data;
}
