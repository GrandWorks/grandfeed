<?php

class Instafeed {
    private $client_id;
    private $token;
    private $count;

    public function __construct($client_id,$token,$count=5)
    { 
        $this->client_id = $client_id;
        $this->token = $token;
        $this->count = $count;
    }

    // public function get_feed()
    // {
    //     if($this->client_id=="" || $this->token=="" || $this->count == 0)
    //     {
    //         return array("error_code" => 443, "message" => "All fields required");
    //     }

    //     $url = "https://api.instagram.com/v1/users/". $this->client_id . "/media/recent?access_token=". $this->token ."&count=".$this->count;
    //     $curl = curl_init();
    //     curl_setopt_array($curl, [
    //         CURLOPT_RETURNTRANSFER => 1,
    //         CURLOPT_URL => $url,
    //         CURLOPT_USERAGENT => 'Instafeed'
    //     ]);
    //     $response = curl_exec($curl);
        
    //     if(curl_error($curl)!='')
    //     {
    //         $response = array("error_code" => curl_errno($curl), "message" => "Error");
    //         curl_close($curl);
    //         return $response;
    //     }
    //     else
    //     {
    //         curl_close($curl);
    //     }

    //     return $response;
    // }

    // public function get_feed_new_api()
    // {
    //     $instagram_posts = instagram_get_user_items( array( 'limit' => 7 ) );
    //     return $instagram_posts;
    // }
    public function get_feed_new_api()
    {
        $token = $this->token;
        $user_id = "3945249985551933";

        // Limit of post fetch 
        $limit = $this->count;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        // Refresh our long time token to make sure it is valid
        $refresh_request_url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token;
        $refresh_response = file_get_contents($refresh_request_url, false, stream_context_create($arrContextOptions));

        // Send a request to the Instagram API to fetch the latest posts
        $medias_request_url = 'https://graph.instagram.com/' . $user_id . '?fields=media&access_token=' . $token;
        $medias_response = json_decode(file_get_contents($medias_request_url, false, stream_context_create($arrContextOptions)));
        $medias = $medias_response->media->data;
        
        $posts = [];
        $i = 0;
        foreach ($medias as $media) {
            if ($i < $limit) {
                // Get details on each posts
                $posts_request_url = "https://graph.instagram.com/" . $media->id . "?fields=thumbnail_url,media_url,permalink,media_type,caption&access_token=" . $token;
                $posts_response = json_decode(file_get_contents($posts_request_url,false, stream_context_create($arrContextOptions)));
                $post = [
                    'media_url' => $posts_response->media_url,
                    'permalink' => $posts_response->permalink,
                    'media_type' => $posts_response->media_type,
                    'feed_url' => $posts_response->permalink,
                ];
                $posts[] = $post;
            }
            $i++;
        }

        // Encode datas in json file
        $content = json_encode($posts);

        return $content;
    }
}