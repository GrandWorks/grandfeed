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

    public function get_feed()
    {
        if($this->client_id=="" || $this->token=="" || $this->count == 0)
        {
            return array("error_code" => 443, "message" => "All fields required");
        }

        $url = "https://api.instagram.com/v1/users/". $this->client_id . "/media/recent?access_token=". $this->token ."&count=".$this->count;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Instafeed'
        ]);
        $response = curl_exec($curl);
        
        if(curl_error($curl)!='')
        {
            $response = array("error_code" => curl_errno($curl), "message" => "Error");
            curl_close($curl);
            return $response;
        }
        else
        {
            curl_close($curl);
        }

        return $response;
    }
}