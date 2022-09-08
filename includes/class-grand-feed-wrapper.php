<?php
require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Instafeed.php' );
require_once(plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Twitterfeed.php' );
require_once(plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-grand-feed-database.php' );

class GrandFeedWrapper {
    private $instagram_client_id = "";
    private $instagram_access_token = "";
    private $instagram_feed_count = "";
    private $twitter_oauth = "";
    private $twitter_oauth_secret = "";
    private $twitter_consumer_key = "";
    private $twitter_consumer_secret = "";
    private $twitter_feed_count = "";
    private $post_count = "";
    private $show_instagram_feed = "";
    private $show_tweet = "";
    private $instafeed = null;
    private $twitter = null;
    private $database = null;

    public function __construct()
    {
        $this->instagram_client_id = get_option('grand-feed-instagram-client-id');
        $this->instagram_access_token = get_option( 'grand-feed-instagram-access-token');
        $this->instagram_feed_count = get_option( 'grand-feed-instagram-feed-count');
        $this->twitter_oauth = get_option( 'grand-feed-twitter-oauth');
        $this->twitter_oauth_secret = get_option( 'grand-feed-twitter-oauth-secret');
        $this->twitter_consumer_key = get_option( 'grand-feed-twitter-consumer-key');
        $this->twitter_consumer_secret = get_option( 'grand-feed-twitter-consumer-secret');
        $this->twitter_feed_count = get_option( 'grand-feed-twitter-feed-count');
        $this->post_count = get_option( 'grand-feed-post-count');
        $this->show_instagram_feed = get_option( 'grand-feed-show-instagram');
        $this->show_tweet = get_option( 'grand-feed-show-twitter');
        
        $this->instafeed = new Instafeed($this->instagram_client_id,$this->instagram_access_token,$this->instagram_feed_count);
        $this->twitter = new Twitterfeed($this->twitter_oauth,$this->twitter_oauth_secret,$this->twitter_consumer_key,$this->twitter_consumer_secret,$this->twitter_feed_count);
        $this->database = GrandFeedDatabase::instantiate();
    }

    public function compileFeeds()
    {
        $compiled_array = array();
        $instafeeds = json_decode($this->instafeed->get_feed_new_api());
        $twitterfeeds = json_decode($this->twitter->get_tweets());
        $twitter_url = "http://twitter.com/".$twitterfeeds[0]->user->screen_name."/status/";
    
        $instagram_array=array();
        if($this->show_instagram_feed==1)
        {
            $insta_temp=array();
            
            foreach ($instafeeds as $key => $value) {
                $image_url = "";
                // if($value->media_type == "VIDEO")
                // {
                //     $image_url = $value->thumbnail_url;
                // }
                // else
                // {
                //     $image_url = $value->media_url;
                // }
                $feed_url = $value->permalink;
                $insta_temp = array(
                    "image_url" => $value->media_url,
                    "feed_url" => $feed_url,
                    "media_type" => $value->media_type
                );
                array_push($instagram_array,$insta_temp);
            }
        }
        
        $twitter_array=array();
        if($this->show_tweet==1)
        {
            $twitter_temp=array();
            $counter=0;
            foreach ($twitterfeeds as $key => $value) {
                if($counter==$this->twitter_feed_count) {break;}
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
            "posts_per_page" => $this->post_count,
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
                    "date" => get_the_date(),
                    "slug" => get_post_field( 'post_name', get_the_ID() )
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

    public function fetch_data($type_array=true)
    {
        $result = $this->database->fetch_feeds();
        if($type_array)
        {
            return json_decode($result);
        }

        return $result;
    }

    public function refresh_data()
    {
        $feeds = $this->compileFeeds();
        $this->database->insert_feeds($feeds);
    }
}