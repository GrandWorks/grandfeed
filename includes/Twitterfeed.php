<?php
include "TwitterAPIExchange.php";

class Twitterfeed {
    private $oauth_access_token;
    private $oauth_access_token_secret;
    private $consumer_key;
    private $consumer_secret;
    private $count;
    private $twitter;

    function __construct($oauth_access_token,$oauth_access_token_secret,$consumer_key,$consumer_secret,$count=5)
    {
        $this->oauth_access_token = $oauth_access_token;
        $this->oauth_access_token_secret = $oauth_access_token_secret;
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->count = $count;
        $this->twitter = new TwitterAPIExchange(array(
            'oauth_access_token' => $this->oauth_access_token,
            'oauth_access_token_secret' => $this->oauth_access_token_secret,
            'consumer_key' => $this->consumer_key,
            'consumer_secret' => $this->consumer_secret,
        ));
    }

    function get_tweets()
    {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $requestMethod = 'GET';
        $getfield = "?include_rts=false&exclude_replies=true&count=100"; 
        return $this->twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
    }


}