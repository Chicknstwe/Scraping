<?php

function tweetFormatText($string) {
	
	$output = '';
	$test = '';
	$input = explode(" ", $string);
	$line_size = 70;
	$line=1;
	$i=0;
	while($i < count($input) && strlen($output) < 290) {
		
		$test = $output . '' . stripslashes($input[$i]);
		
		if(strlen($input[$i]) <= $line_size) {
			if(strlen($test) > $line_size * $line) {
				$output = $output . "\n" . stripslashes($input[$i]) . " ";
				$line++;
			} else {
				$output = $output . "" . stripslashes($input[$i]) . " ";
			}
		} else {
			$output = $output . '' . wordwrap(stripslashes($input[$i]), $line_size, "\n", true) . ' ';
			$line = floor(strlen($output)/$line_size) + 1;
		}
			
		$i++;
	}
	return substr($output, 0, -1);
}

function formatTweetNumber($n) {
	
	$mill = $n / 1000000;
	$thou = $n / 1000;
	$r_mill = round($mill, 1, PHP_ROUND_HALF_DOWN);
	$f_mill = floor($mill);
	$r_thou = round($thou, 1, PHP_ROUND_HALF_DOWN);
	$f_thou = floor($thou);
	
	if($n >= 1000000) {
		if($r_mill == $f_mill) {
			$output = number_format($f_mill, 0, ',', '.');
		} else {
			$output = number_format($r_mill, 1, ',', '.');
		}
		$output = $output . 'M';
	} elseif($n > 9999) {
		if($r_thou == $f_thou) {
			$output = number_format($f_thou, 0, ',', '.');
		} else {
			$output = number_format($r_thou, 1, ',', '.');
		}
		$output = $output . 'K';
	} else {
		$output = $n;
	}
	
	return $output;
	
}

function displayTempTable($array){
	
	if(count($array) > 0) {
		echo '<table class="table-fill"><tr bgcolor="00a699" width=\"200\">';
		echo '<th colspan=4><font color="#FFFFFF">' . $array[0][1] . '<strong>@' . utf8_encode($array[0][7]) . '</strong>' . utf8_encode($array[0][2]) . '</font></th></tr>';
		echo '<tr><td>Fecha</td><td>Tweet</td><td>Favs / RT</td><td>Media</td><td>Links</td></tr>';
		
		$i=0;
		foreach($array as $tweet) {
			$i++;
			echo '<tr><td>' . $tweet[0] . '</td><td>' . $tweet[3] . '</td><td>' . $tweet[8] . ' / ' . $tweet[9] . '</td><td>';
			
			$i=0;
			foreach($tweet[10] as $media) {
				$i++;
				if($i > 1) {
					echo '<br>';
				}
				echo '<a href="' . $media . '" target="_blank">Media ' . $i . '</a>';
			}
			
			echo '</td><td>';
			
			$i=0;
			foreach($tweet[11] as $link) {
				$i++;
				if($i > 1) {
					echo '<br>';
				}
				echo '<a href="' . $link . '" target="_blank">Link ' . $i . '</a>';
			}
			
			echo '</td></tr>';
		}
		
		echo '</table>';
	}
}

function calcBasePx($string) {
	
	$n=0;
	
	for($i=0;$i<strlen($string);$i++) {
		if(substr($string, $i, 1) == 'm') {
			$n = $n + 14;
		} else {
			$n = $n + 8;
		}
	}
	
	return $n;
}

function convert_tweet_to_png($tweet) {
	
	$name = $tweet[7];
	$screen_name = $tweet[2];
	$text = $tweet[3];
	$raw_date = $tweet[0];
	$date = new tweetDate();
	$date->newdate($raw_date);
	$favs = formatTweetNumber($tweet[8]);
	$retweets = formatTweetNumber($tweet[9]);
	
	$avatar = imagecreatefromjpeg($tweet[11]);
	$avatar_frame = imagecreatefrompng('app/imgs/twitter_white_frame.png');
	$raw_image = imagecreatefrompng('app/imgs/text_clean.png');
	$base_tweet = imagecreatefrompng('app/imgs/text_clean.png');
	
	
	$font_path = 'app/font/arial.ttf';
	$font_pathbd = 'app/font/arialbd.ttf';
	$black = imagecolorallocate($base_tweet, 0, 0, 0);
	$grey = imagecolorallocate($base_tweet, 111, 118, 138);
	/* Transparencies are not properly handled without the following 3 lines */
	$transparent_tw = imagecolorallocatealpha($base_tweet, 0, 0, 0, 127);
	imagefill($base_tweet, 0, 0, $transparent_tw);
	imagealphablending($base_tweet, true); 
	
	$text = tweetFormatText($text);
	$base_px = calcBasePx($name);
	$file_path = 'PNG tweets/' . $screen_name . '/' . $name . ' - ' . $raw_date . ' - ' . $tweet[4] . '.png';
	if (!file_exists('PNG tweets')) mkdir(utf8_decode('PNG tweets'), 0777, true);
	if (!file_exists('PNG tweets/' . $screen_name)) mkdir(utf8_decode('PNG tweets/' . $screen_name), 0777, true);
	$display_name = '@' . $screen_name . ' · ' . $date->getDay() . ' ' . $date->getMonthName() . '. ' . $date->getYear() . ' ' . $date->getFullTime();
	
	imagecopy($base_tweet, $raw_image, 0, 0, 0, 0, imagesx($raw_image), imagesy($raw_image));
	imagettftext($base_tweet, 11, 0, 70 + 10 + $base_px, 23, $grey, $font_path, $display_name);
	imagecopy($base_tweet, $avatar, 13, 11, 0, 0, imagesx($avatar), imagesy($avatar));
	imagecopy($base_tweet, $avatar_frame, 13, 11, 0, 0, imagesx($avatar_frame), imagesy($avatar_frame));
	imagettftext($base_tweet, 11, 0, 70, 23, $black, $font_pathbd, $name);
	imagettftext($base_tweet, 11, 0, 70, 45, $black, $font_path, $text);
	imagettftext($base_tweet, 9, 0, 260, 167, $grey, $font_pathbd, $favs);
	imagettftext($base_tweet, 9, 0, 180, 167, $grey, $font_pathbd, $retweets);
	
	imagepng($base_tweet, 'PNG tweets/' . $screen_name . '/' . $tweet[4] . '.png');
	
	imagedestroy($base_tweet);
	imagedestroy($raw_image);
	imagedestroy($avatar);
	imagedestroy($avatar_frame);
	
}
Class tweetDate{
	
	// $date example: "Tue Apr 01 05:37:38 +0000 2014"
	
	var $tdate = "";
	var $ini = false;
	
	function newDate($input){
			$this->tdate = $input;
			$this->ini = true;
			return true;
	}
	
	function getDayName(){
		if($this->ini == true) {
			return substr($this->tdate, 0, 2);
		} else {
			return "You must set the date.";			
		}
	}

	function getMonthName(){
		if($this->ini == true) {
			return substr($this->tdate, 4, 3);
		} else {
			return "You must set the date.";			
		}
	}
	
	function getMonth(){
		if($this->ini == true) {
			$months = array("Jan" => 1, "Feb" => 2, "Mar" => 3, "Apr" => 4, "May" => 5, "Jun" => 6, "Jul" => 7, "Aug" => 8, "Sep" => 9, "Oct" => 10, "Nov" => 11, "Dec" => 12);
			$name = substr($this->tdate, 4, 3);
		
			return $months[$name];
			
		} else {
			return "You must set the date.";			
		}
	}
	
	function getDay(){
		if($this->ini == true) {
			return substr($this->tdate, 8, 2);
		} else {
			return "You must set the date.";			
		}
	}
	
	function getFullTime(){
		if($this->ini == true) {
			return substr($this->tdate, 11, 8);
		} else {
			return "You must set the date.";			
		}
	}
	
	function getHour(){
		if($this->ini == true) {
			return substr($this->tdate, 11, 2);
		} else {
			return "You must set the date.";			
		}
	}
	
	function getMin(){
		if($this->ini == true) {
			return substr($this->tdate, 14, 2);
		} else {
			return "You must set the date.";			
		}
	}
	
	function getSec(){
		if($this->ini == true) {
			return substr($this->tdate, 17, 2);
		} else {
			return "You must set the date.";			
		}
	}
	
	function getYear(){
		if($this->ini == true) {
			return substr($this->tdate, -4);
		} else {
			return "You must set the date.";			
		}
	}	
}

?>