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
	  while ($i < $size) {
		  $out = $out . "'" . $arr[$i] . "'";
		  $i++;
		  if ($i < $size) {
			  $out = $out . ", ";
		  }
	  }
	  $out = $out . ");\n";

	return $out;
}

function keywords_matches($path) {
	include 'resources.php';
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

function addResources($websites, $names, $keywords, $added, $img_added, $docs_added) {
	
	$resources = fopen("resources.php", "w") or die("¡Error al abrir resources.php!");
	fwrite($resources, "<?php\n");
	fwrite($resources, arrayToStringC($websites, 0));
	fwrite($resources, arrayToStringC($names, 1));
	fwrite($resources, arrayToStringC($keywords, 2));
	fwrite($resources, arrayToStringC($added, 3));
	fwrite($resources, arrayToStringC($img_added, 4));
	fwrite($resources, arrayToStringC($docs_added, 5));
	fwrite($resources, " ?>");
	fclose($resources);
	
}

function isConnected()
{
    // use 80 for http or 443 for https protocol
    $connected = @fsockopen("www.example.com", 80);
    if ($connected){
        fclose($connected);
        return true; 
    }
    return false;
}

function fixResources() {
	
	$websites = array();
	$names = array();
	$keywords = array();
	$added = array();
	$img_added = array();
	$docs_added = array();
	$resources = fopen("resources.php", "w") or die("¡Error al abrir resources.php!");
	fwrite($resources, "<?php\n");
	fwrite($resources, arrayToStringC($websites, 0));
	fwrite($resources, arrayToStringC($names, 1));
	fwrite($resources, arrayToStringC($keywords, 2));
	fwrite($resources, arrayToStringC($added, 3));
	fwrite($resources, arrayToStringC($img_added, 4));
	fwrite($resources, arrayToStringC($docs_added, 5));
	fwrite($resources, " ?>");
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
	$forb_chars = array('?', '/', '|', '<', '>', '"', ':', '*');
	$s = str_replace($forb_chars, "-", $s);
	
	return $s;
}

 ?>