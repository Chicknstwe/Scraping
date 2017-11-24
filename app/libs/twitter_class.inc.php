<?php

class Twitter{
	
    function getTweets($user, $number){
        ini_set('display_errors', 1);
        require_once('TwitterAPIExchange.php');
		include 'twitter_auth.inc.php';

        $settings = array(
            'oauth_access_token' => $oauth_access_token,
            'oauth_access_token_secret' => $oauth_access_token_secret,
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret
        );
		
		$json = '{}';

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';       
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($settings);
		
		$page = 1;
		while($number > 0) {
			$getfield = '?screen_name=' . $user . '&count=200&page=' . $page; 
			$temp_json =  $twitter->setGetfield($getfield)
						 ->buildOauth($url, $requestMethod)
						 ->performRequest();
			$page++;
			$number -= 200;
			
			
			$json = json_encode(array_merge(json_decode($json, true),json_decode($temp_json, true)));
			
		}
		
        return $json;

    }
	
    function getStatus(){
        ini_set('display_errors', 1);
        require_once('TwitterAPIExchange.php');
		include 'twitter_auth.inc.php';

        $settings = array(
            'oauth_access_token' => $oauth_access_token,
            'oauth_access_token_secret' => $oauth_access_token_secret,
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret
        );
		
		$url = 'https://api.twitter.com/1.1/application/rate_limit_status.json';
		$requestMethod = 'GET';
		$qry = '?resources=statuses';
		$twitter = new TwitterAPIExchange($settings);

		$res = $twitter->setGetfield($qry)->buildOauth($url, $requestMethod)->performRequest();
	
		return $res;
	}
	
	function getLimit($resraw){
		
        $res = json_decode($resraw, true);
		$limit = $res->user_timeline;

        return $limit;
    }


    function getArrayTweets($jsonraw){
        $rawdata = array();
        $json = json_decode($jsonraw);
        $num_items = count($json);
		
		if(is_array($json)) {		
		
			for($i=0; $i<$num_items; $i++){
				$user = $json[$i];
				
				$fecha = $user->created_at;
				$url_imagen = $user->user->profile_image_url;
				$screen_name = $user->user->screen_name;
				$tweet = $user->text;
				$tid = (string)$user->id_str;
				$name = $user->user->name;
				$favs = $user->favorite_count;
				$retweets = $user->retweet_count;
				
				$entities = [];
				if(property_exists( $user->entities, 'media' )) {
					foreach($user->entities->media as $item)
					{
						array_push($entities, $item->media_url);
					}
				}
				
				if(property_exists( $user->entities, 'urls' )) {
					foreach($user->entities->urls as $item)
					{
						array_push($entities, $item->url);
					}
				}
				
				$hashtags = [];
				if(property_exists( $user->entities, 'hashtags' )) {
					foreach($user->entities->hashtags as $item)
					{
						$hash = '#' . $item->text;
						array_push($hashtags, $hash);
					}
				}

				$imagen = '<a href="https://twitter.com/'.$screen_name.'" target=_blank><img src="app/avatar/'.valid_chars(basename(urldecode($url_imagen))).'"></img></a>';
				$url = "<a href='https://twitter.com/".$screen_name."' target=_blank>@".$screen_name."</a>";

				$rawdata[$tid][0]=$fecha;
				$rawdata[$tid][1]=$imagen;
				$rawdata[$tid][2]=$screen_name;
				$rawdata[$tid][3]=$tweet;
				$rawdata[$tid][4]=$tid;
				$rawdata[$tid][5]=$url;
				$rawdata[$tid][6]=$url_imagen;
				$rawdata[$tid][7]=$name;
				$rawdata[$tid][8]=$favs;
				$rawdata[$tid][9]=$retweets;
				$rawdata[$tid][10]=$entities;
				$rawdata[$tid][11]= 'app/avatar/' . valid_chars(basename(urldecode($url_imagen)));
				$rawdata[$tid][12]=$hashtags;
				
			}
			return $rawdata;
		} else { 
			return "The user does not exist or you have entered wrong tokens.";
		}
    }
}

?>