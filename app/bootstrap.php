<?php

define( 'API_ROOT', dirname( __FILE__) );

foreach (glob(API_ROOT . '/libs/*.inc.php') as $lib) {
  include $lib;
}

if (!file_exists(API_ROOT . '/data/web')) mkdir(API_ROOT . '/data/web', 0777, true);
if (!file_exists(API_ROOT . '/data/twitter')) mkdir(API_ROOT . '/data/twitter', 0777, true);

// Get all resources
$json_resources = json_decode(file_get_contents(API_ROOT . '/data/resources.json'), TRUE);
$websites = $json_resources['websites'];
$names = $json_resources['names'];
$keywords = $json_resources['keywords'];
$twitter_accs = $json_resources['twitter_accs'];

// Get all twitter oauth tokens
$json_twitter_auth = json_decode(file_get_contents(API_ROOT . '/data/twitter_auth.json'), TRUE);
$oauth_access_token = $json_twitter_auth['oauth_access_token'];
$oauth_access_token_secret = $json_twitter_auth['oauth_access_token_secret'];
$consumer_key = $json_twitter_auth['consumer_key'];
$consumer_secret = $json_twitter_auth['consumer_secret'];

?>
