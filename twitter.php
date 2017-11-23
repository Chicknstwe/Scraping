<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' ); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Tweets collector</title>
<link rel="stylesheet" type="text/css" href="/css/twitter.css" media="screen" />
<script src="/js/tools.js"></script>
</head>
<body>
	<nav class="parent-menu">
	<ul>
     <li class="sub-menu-parent">
       <a class="title" href="scraping.php">Web scraping</a>
       <ul class="sub-menu">
         <li><a class="link" href="scraping.php">Scraping tool</a></li>
         <li><a class="link" href="explorer.php">Results explorer</a></li>
         <li><a class="link" href="results_edit.php?results=webs">Web results edition</a></li>
       </ul>
     </li>
     <li class="sub-menu-parent"><a class="title" href="twitter.php">Twitter API</a>
       <ul class="sub-menu">
         <li><a class="link" href="twitter.php">Tweets collector</a></li>
         <li><a class="link" href="explorer.php">Results explorer</a></li>
		 <li><a class="link" href="results_edit.php?results=tweets">Twitter results edition</a></li>
		 <li><a class="link" href="tweets_to_png.php">Tweet-to-PNG converter</a></li>
		 <li><a class="link" href="excel_export.php">Excel exporter</a></li>
       </ul>
     </li>
     <li class="sub-menu-parent"><a class="title" href="config.php">Configuration</a>
       <ul class="sub-menu">
         <li><a class="link" href="config.php">Settings</a></li>
		 <li><a class="link" href="manual.html">Manual</a></li>
         <li><a class="link" href="faq.html">FAQ</a></li>
		 <li><a class="link" href="about.html">About</a></li>
       </ul></li>
   </ul>
	</nav>
	<br><br><br><table class="table-fill"><form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
	<tr><th>Tweets collector</th></tr>
	<tr><td>Choose the account
	<select name="twitter_user_select" size="1">
	
<?php
	foreach ($twitter_accs as $acc) {
?>
			<option value="<?php echo $acc; ?>">@<?php echo urldecode($acc); ?></option>
<?php
	}
?>	

   </select></td></tr>
   
	<tr><td>Number of tweets <input type="range" name="exec_n_range" min="15" max="200" value="15" step="1" id="n_exec" onchange="range_exec.value=value"><output align="center" id="range_exec">15</output></td></tr>
	<tr><td><input type="checkbox" id="checkall" onchange="checkAllKeywords()"> <strong>Keywords to search</strong><br />
<?php
	$wordInLine = 1;
	foreach($keywords as $word) {
?>
		<input type="checkbox" name="selected_keywords[]" value="<?php echo $word;?>" /> <?php echo $word;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
		$wordInLine++;
		if($wordInLine > 3) {
			$wordInLine = 1;
?>
		<br />
<?php
		}
	}
?>
	</td></tr>
	<tr><td><input type="checkbox" name="only_new" value="Yes" checked> Only new tweets<br>
	<input type="checkbox" name="only_keyword" value="Yes"> Only keywords matches<br>
	<input type="checkbox" name="show_info" value="Yes"> Show performance report</td></tr>
    <tr><td><input type="submit" name="submit" class="amp" value="Run"/></td></tr></form></table><br><br><br>

<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
	
  
	if(isConnected()) {

		$name = $_POST['twitter_user_select'];
		$n_exec = $_REQUEST["exec_n_range"];
		if(isset($_REQUEST["selected_keywords"])) {
			$selected_keywords = $_REQUEST["selected_keywords"];
		}
		$tweets_searched = 0;
		$new_tweets_found = 0;
		$keyword_matches = 0;
		$tweets_saved = 0;
		
		$twitterObject = new Twitter();
		
		/* There's no need to check limits with this code since it doesn't perform any rate limited action.
		   Anyway, here's the code that calls the functions used to check it.
		   
		$resources = $twitterObject->getStatus();
		$limit = $twitterObject->getLimit($resources);*/
		
		$jsonraw =  $twitterObject->getTweets($name, $n_exec);
		$rawdata =  $twitterObject->getArrayTweets($jsonraw);
		
		if (gettype($rawdata) != "string") {
		
			$array_output = array();
			
			if((isset($_POST['only_new']) && $_POST['only_new'] == 'Yes') && (isset($_POST['only_keyword']) && $_POST['only_keyword'] == 'Yes')) {
				foreach($rawdata as $tweet) {
					if((!array_key_exists($tweet[2], $twitter_reg) || !in_array($tweet[4], $twitter_reg[$tweet[2]])) && hasKeyword($tweet[3], $selected_keywords)) {
						$tweets[$tweet[2]][$tweet[4]] = $tweet;
						if(!in_array($tweet[2], $twitter_reg)) {
							$twitter_reg[$tweet[2]] = array();
						}
						array_push($array_output, $tweet);
						addTwitterReg($tweets, $twitter_reg);
						save_avatar($tweet[6]);
						$keyword_matches++;
						$new_tweets_found++;
					}
					$tweets_searched++;
				}
			} elseif(isset($_POST['only_new']) && $_POST['only_new'] == 'Yes') {
				echo "<br />";
				echo "<br />";
				foreach($rawdata as $tweet) {
					if(!array_key_exists($tweet[2], $twitter_reg) || !in_array($tweet[4], $twitter_reg[$tweet[2]])) {
						$tweets[$tweet[2]][$tweet[4]] = $tweet;
						if(!array_key_exists($tweet[2], $twitter_reg)) {
							$twitter_reg[$tweet[2]] = array();
						}
						array_push($array_output, $tweet);
						addTwitterReg($tweets, $twitter_reg);
						save_avatar($tweet[6]);
						$new_tweets_found++;
					}
					$tweets_searched++;
				}
			} elseif(isset($_POST['only_keyword']) && $_POST['only_keyword'] == 'Yes') {
				foreach($rawdata as $tweet) {
					if(hasKeyword($tweet[3], $selected_keywords)) {
						$tweets[$tweet[2]][$tweet[4]] = $tweet;
						array_push($array_output, $tweet);
						addTwitterReg($tweets, $twitter_reg);
						save_avatar($tweet[6]);
						$keyword_matches++;
					}
					$tweets_searched++;
				}
			} else {
				foreach($rawdata as $tweet) {
					$tweets[$tweet[2]][$tweet[4]] = $tweet;
					array_push($array_output, $tweet);
					addTwitterReg($tweets, $twitter_reg);
					save_avatar($tweet[6]);
					$tweets_searched++;
				}
				
			}	
			
			foreach($array_output as $tweet) {
				if(!array_key_exists($tweet[2], $twitter_reg)) {
					$twitter_reg[$tweet[2]] = array();
				}
				array_push($twitter_reg[$tweet[2]], $tweet[4]);
			}
			
			addTwitterReg($tweets, $twitter_reg);
			
			if (isset($_POST['show_info']) && $_POST['show_info'] == 'Yes') {
?>
				<br><br><br>";
				<table class="table-fill"><tr><th colspan=2>Performance report</th></tr>
				<tr><td>Tweets processed</td><td><?php echo $tweets_searched; ?></td></tr>
				<tr><td>New tweets processed</td><td><?php echo $new_tweets_found; ?></td></tr>
				<tr><td>Keywords matches</td><td><?php echo $keyword_matches; ?></td></tr>
				</table>
				<br><br><br>
<?php
			}
?>
				<script language="javascript">alert("The script has been executed.");</script>
<?php	
		} else {
?>
			<script language="javascript">alert("<?php echo $rawdata; ?>");</script>
<?php			
		}
			
	} else {
?>
		<script language="javascript">alert("You are not connected to the internet.");</script>
<?php	
	}
	
}
?>
</body>
</html>
