<?php

function arrayToStringC($arr, $type) {
	$i=0;
	$size=sizeof($arr);
	$out = "";
	if ($type == 0) $out = $out . '$websites = array(';
	if ($type == 1) $out = $out . '$names = array(';
	if ($type == 2) $out = $out . '$keywords = array(';
	if ($type == 3) $out = $out . '$added = array(';
	if ($type == 4) $out = $out . '$img_added = array(';
	if ($type == 5) $out = $out . '$docs_added = array(';
	if ($type == 8) $out = $out . '$twitter_accs = array(';
	if ($type == 6) {
		$out = $out . '$tweets = array(';
		$keys = array_keys($arr);
	}
	if ($type == 7) {
		$out = $out . '$twitter_reg = array(';
		$keys = array_keys($arr);
	}
	
	  while ($i < $size) {
		  if ($type == 1) {
			  $x = 1;
			  foreach($arr as $key => $value) {
				  $out = $out . "'" . $key . "' => '" . $value . "'";
				  if($x < sizeof($arr)) {
					  $out = $out . ", ";
				  }
				  $x++;
			  }
		  } elseif ($type == 6) {
			  $out = $out . '"' . $keys[$i] . '" => array(';
			  $account = $arr[$keys[$i]];
			  $acc_keys = array_keys($account);
			  $x = 0;
			  while ($x < sizeof($account)) {
				  $out = $out . '"' . $acc_keys[$x] . '" => array(';
				  $temp = $account[$acc_keys[$x]];
				  $k = 0;
				  while ($k < sizeof($temp)) {
					  if (is_array($temp[$k])) {
						  $temp_array = $temp[$k];
						  $out = $out . 'array(';
						  $j = 0;
						  while($j < sizeof($temp_array)) {
							  $out = $out . '"' . sanitize_tweet_reg_input($temp_array[$j]) . '"';
							  $j++;
							  if ($j < sizeof($temp_array)) {
								  $out = $out . ", ";
							  }
						  }
						  $out = $out . ")";
					  } else $out = $out . '"' . sanitize_tweet_reg_input($temp[$k]) . '"';
					  $k++;
					  if ($k < sizeof($temp)) {
						  $out = $out . ", ";
					  }
				  }
				  $out = $out . ")";
				  $x++;
				  if ($x < sizeof($account)) {
					  $out = $out . ", ";
				  }
			  }
			  $out = $out . ")";
		  } elseif ($type == 7) {
			  $out = $out . '"' . $keys[$i] . '" => array(';
			  $account = $arr[$keys[$i]];
			  $x = 0;
			  while ($x < sizeof($account)) {
				  $out = $out . '"' . $account[$x] . '"';
				  $x++;
				  if ($x < sizeof($account)) {
					  $out = $out . ", ";
				  }
			  }
			  $out = $out . ")";
		  } else {
			  $out = $out . '"' . $arr[$i] . '"';
		  }
		  $i++;
		  if ($i < $size) {
			  $out = $out . ", ";
		  }
	  }
	  $out = $out . ");\n";

	return $out;
}

function modSpace($string) {
	
	if(strpos($string, '__0__')) {
		$string = str_replace('__0__', ' ', $string);
	} elseif(strpos($string, ' ')) {
		$string = str_replace(' ', '__0__', $string);
	}
	return $string;
}

function keywords_matches($path, $keywords) {
	$input = file_get_contents_curl($path);
	$matches = "";
	$found = false;
	$i=0;
	while ($i < sizeof($keywords)) {
		if (strpos($input, $keywords[$i]) !== false) {
			
			if ($found == false) {
				$matches = $keywords[$i];
				$found = true;
			} else {
				$matches = $matches . ', ' . $keywords[$i];
			}
		} 
		$i++;
	}
	
	return $matches;
}

function static_keywords_matches($path) {
	$json_resources = json_decode(file_get_contents(__DIR__ . '/../data/resources.json'), TRUE);
	$keywords = $json_resources['keywords'];
	$input = file_get_contents_curl($path);
	$matches = "";
	$found = false;
	$i=0;
	while ($i < sizeof($keywords)) {
		if (strpos($input, $keywords[$i]) !== false) {
			
			if ($found == false) {
				$matches = $keywords[$i];
				$found = true;
			} else {
				$matches = $matches . ', ' . $keywords[$i];
			}
		} 
		$i++;
	}
	
	return $matches;
}

function tweet_keywords_matches($input, $keywords) {
	$matches = "";
	$found = false;
	$i=0;
	while ($i < sizeof($keywords)) {
		if (strpos($input, $keywords[$i]) !== false) {
			
			if ($found == false) {
				$matches = $keywords[$i];
				$found = true;
			} else {
				$matches = $matches . ', ' . $keywords[$i];
			}
		} 
		$i++;
	}
	
	return $matches;
	
}

function static_tweet_keywords_matches($input) {
	$json_resources = json_decode(file_get_contents(__DIR__ . '/../data/resources.json'), TRUE);
	$keywords = $json_resources['keywords'];
	$matches = "";
	$found = false;
	$i=0;
	while ($i < sizeof($keywords)) {
		if (strpos($input, $keywords[$i]) !== false) {
			
			if ($found == false) {
				$matches = $keywords[$i];
				$found = true;
			} else {
				$matches = $matches . ', ' . $keywords[$i];
			}
		} 
		$i++;
	}
	
	return $matches;
	
}

function addResources($websites, $names, $keywords, $twitter_accs) {
	
	$resources = fopen("app/libs/resources.inc.php", "w") or die("¡Error opening resources.inc.php!");
	fwrite($resources, "<?php\n");
	fwrite($resources, arrayToStringC($websites, 0));
	fwrite($resources, arrayToStringC($names, 1));
	fwrite($resources, arrayToStringC($keywords, 2));
	fwrite($resources, arrayToStringC($twitter_accs, 8));
	fwrite($resources, "?>");
	fclose($resources);
	
}

function addTwitterDevCredentials($oauth, $oauth_secret, $consumer, $consumer_secret) {
	
	$resources = fopen("app/libs/twitter_auth.inc.php", "w") or die("¡Error opening twitter_auth.inc.php!");
	fwrite($resources, "<?php\n");
	fwrite($resources, stringToWriteTDC($oauth, 0));
	fwrite($resources, stringToWriteTDC($oauth_secret, 1));
	fwrite($resources, stringToWriteTDC($consumer, 2));
	fwrite($resources, stringToWriteTDC($consumer_secret, 3));
	fwrite($resources, "?>");
	fclose($resources);
}

function stringToWriteTDC($input, $type) {
	
	$output = '';
	switch($type) {
		case 0:
			$output = $output . '$oauth_access_token = ';
			break;
			
		case 1:
			$output = $output . '$oauth_access_token_secret = ';
			break;
			
		case 2:
			$output = $output . '$consumer_key = ';
			break;
			
		case 3:
			$output = $output . '$consumer_secret = ';
			break;
	}
	
	$output = $output . "\"" . $input . "\";\n";
	
	return $output;
}

function isConnected()
{
    $connected = @fsockopen("www.google.com", 80);
    if ($connected){
        fclose($connected);
        return true; 
    }
    return false;
}

function scrapRegReset() {
	
	foreach (glob('app/data/web/*.json') as $file_path) {
		unlink($file_path);
	}	
}

function twitterRegReset() {
	
	foreach (glob('app/data/twitter/*.json') as $file_path) {
		unlink($file_path);
	}	
	
}

function resRegReset() {
	
	$websites = array();
	$names = array();
	$keywords = array();
	$twitter_accs = array();
	$resources = fopen("app/data/resources.json", "w") or die("¡Error opening resources.json!");
	fwrite($resources, '{"websites":[],"names":{},"keywords":[],"twitter_accs":[]}');
	fclose($resources);
	
}


function file_get_contents_curl($url) {
  if (strpos($url,'http://') !== FALSE) {
    $fc = curl_init();
    curl_setopt($fc, CURLOPT_URL,$url);
    curl_setopt($fc, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($fc, CURLOPT_HEADER,0);
    curl_setopt($fc, CURLOPT_VERBOSE,0);
    curl_setopt($fc, CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($fc, CURLOPT_TIMEOUT,30);
    $res = curl_exec($fc);
    curl_close($fc);
  } elseif (strpos($url,'https://') !== FALSE) {
	$arrContextOptions=array(
      "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );  

    $res = file_get_contents($url, false, stream_context_create($arrContextOptions));  
  } else $res = file_get_contents($url);
  return $res;
}

function pc_link_extractor($s) {
  $a = array();
  if (preg_match_all('/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*?)<\/a>/i',
                     $s,$matches,PREG_SET_ORDER)) {
    foreach($matches as $match) {
      array_push($a,array($match[1],$match[2]));
    }
  }
  return $a;
}

function array_tag_img_extractor($s) {
  $a = array();
  if (preg_match_all('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i',
                     $s,$matches,PREG_SET_ORDER)) {
    foreach($matches as $match) {
      array_push($a, $match[1]);
    }
  }
  return $a;
}

function tag_img_extractor($s) {
  if (preg_match('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i', $s, $matches)) {
	  return $matches[1];
  } else {
	  return 0;
  }
}

function valid_chars($s) {
	$forb_chars = array('?', '/', '|', '<', '>', '"', ':', '*', '&');
	$s = str_replace($forb_chars, "-", $s);
	
	return $s;
}

function onlyNewTweets($array) {
	
	include 'twitter_reg.inc.php';
	
	$output = '';
    $num = count($array);
	
    for($i=0; $i<$num; $i++) {
		if(!in_array($array[$i][4], $twitter_reg)) {
			array_push($output, $array[$i]);
		}
	}
	
	return $output;
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function hasKeyword($string, $keywords) {
	
	foreach($keywords as $key) {
		if(strpos($string, $key)) {
			return true;
		}
	}
	return false;
}

function rrmdir($dir) {
   if (is_dir($dir)) {
	 $objects = scandir($dir);
	 foreach ($objects as $object) {
	   if ($object != "." && $object != "..") {
		 if (filetype($dir."/".$object) == "dir") rrmdir(($dir."/".$object)); 
		 else unlink($dir."/".$object);
	   }
	 }
	 reset($objects);
	 rmdir($dir);
   }
}

function deleteFile($path) {
	
	if (is_file($path))	{ 
	  unlink($path);
	} elseif (is_dir($path)) {
	  rrmdir($path);
	} 
	if (is_file(utf8_decode($path))) {
	  unlink(utf8_decode($path));
	} elseif (is_dir(utf8_decode($path))) {
	  rrmdir(utf8_decode($path));
	}
	if (is_file(urldecode($path))) {
	  unlink(urldecode($path));
	} elseif (is_dir(urldecode($path))) {
	  rrmdir(urldecode($path));
	}
	if (is_file(urlencode($path))) {
	  unlink(urlencode($path));
	} elseif (is_dir(urlencode($path))) {
	  rrmdir(urlencode($path));
	}
}

function save_avatar($url) {
	
	$img_file = file_get_contents_curl($url);
	$path = 'app/avatar/';
	if (!file_exists($path)) mkdir(utf8_decode($path), 0777, true);
	$get_img = fopen($path . "" . valid_chars(basename(urldecode($url))), "w") or die("¡Error opening " . $path . "" . valid_chars(basename(urldecode($url))));
	fwrite($get_img, $img_file);
	fclose($get_img);
	
}

function tweetDateFormatTable($raw_date) {
	
	$date = new tweetDate();
	$date->newdate($raw_date);
	
	return $date->getYear() . '-' . $date->getMonth() . '-' . $date->getDay() . ' ' . $date->getFullTime() . ' ' . $date->getDayName();
	
}

function sanitize_tweet_reg_input($input) {
	
	$input = str_replace("\\\\'", "\\'", $input);
	$input = str_replace('\\\\"', '\\"', $input);
	$input = str_replace("'", "\\'", $input);
	$input = str_replace('"', '\\"', $input);
	
	return $input;
}

function dir_is_empty($dir) {
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}
?>
