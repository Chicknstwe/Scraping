<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' ); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Tweets-to-PNG converter</title>
<script src="/js/tools.js"></script>
<link rel="stylesheet" type="text/css" href="/css/results_edit.css" media="screen" />
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
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
	$acc = $_REQUEST['button'];
	if(isset($_REQUEST['convert_tweets_' . $acc])) {
		$input = $_REQUEST['convert_tweets_' . $acc];
	} else {
		$input = array();
	}
	$converted_tweets = 0;
	if(sizeof($input) > 0) {
		$account = explode('.', $input[0])[0];
		$tweets_file = json_decode(file_get_contents('app/data/twitter/' . $account . '.json'), TRUE);
	
		foreach($input as $raw_tw) {
			$tw = explode('.', $raw_tw);
			convert_tweet_to_png($tweets_file[$tw[1]]);
			$converted_tweets++;
		}
?>
		<script language="javascript">alert("<?php echo $converted_tweets; ?> tweets have been converted into PNG images. They can be found at <?php echo addslashes(dirname(__FILE__)) . '/PNG tweets/' . $account; ?>");</script>
	
<?php
	} else {
?>
		<script language="javascript">alert("You must choose at least one tweet.");</script>
<?php
	}
}
?>
<br>
<br>
<br>
<?php	

	foreach(glob('app/data/twitter/*.json') as $json_file) {
		$account = basename($json_file, '.json');
		$content = json_decode(file_get_contents($json_file), TRUE);
		if (sizeof($content) > 0) {
?>
			<p><strong><?php echo 'Tweets: @' . $account; ?></strong> <a class="toggle" id="<?php echo 'tweets' . $account . 'toggle';?>" href="javascript:showhide('<?php echo 'tweets' . $account;?>');">+</a></p>
			<table class="sortable"  style="display: none;" id="<?php echo 'tweets' . $account;?>"><form action="tweets_to_png.php" method="post" enctype="multipart/form-data">
			<tr><th><input type="checkbox" id="checkall_convert_tweets_<?php echo utf8_encode($account);?>" onchange="checkAllElements('convert_tweets_<?php echo utf8_encode($account);?>')"></th><th class="tweet-cell">Date</th><th>Tweet</th><th>Keywords</th><th>Favs</th><th>RT</th></tr>
<?php
			foreach($content as $tweet_id => $tweet) {
?>
				<tr><td><input type="checkbox" name="convert_tweets_<?php echo utf8_encode($account);?>[]" value="<?php echo $account . '.' . $tweet[4]?>"></td><td class="left-col-tw"><?php echo tweetDateFormatTable($tweet[0]); ?></td><td class="left-col"><?php echo stripslashes($tweet[3]); ?></td><td><?php echo static_tweet_keywords_matches($tweet[3]); ?></td><td><?php echo $tweet[8]; ?></td><td><?php echo $tweet[9]; ?></td></tr>
<?php
			}
			
?>
			</table>
			<table class="sorttable-lit" style="display: none;" id="<?php echo 'tweets' . $account . 'delete'; ?>">
			<tr><td class="button"><button type="submit" class="amp" name="button" value="<?php echo $account; ?>">Convert</button></td></tr>
			</form></table>
<?php
			
		}
	}
	
?>
<br>
<br>
</body>
</html>