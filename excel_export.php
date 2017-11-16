<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' ); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Excel exporter</title>
<script src="/js/sorttable.js"></script>
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
	if(isset($_REQUEST['export_tweets'])) {
		$input = $_REQUEST['export_tweets'];
		require_once 'Spreadsheet/Excel/Writer.php';
	} else {
		$input = array();
	}
	$exported_tweets = 0;
	if(sizeof($input) > 0) {
		$account = explode('.', $input[0])[0];
		if (!file_exists('Export')) mkdir(utf8_decode('Export'), 0777, true);
		$workbook = new Spreadsheet_Excel_Writer('Export/' . $account . ' - ' . date('Y-m-d') . ' ' . date('H-i-s') . '.xls');
		$worksheet =& $workbook->addWorksheet(date('Y-m-d'));
		$worksheet->write(0, 0, 'Name');
		$worksheet->write(0, 1, 'Account');
		$worksheet->write(0, 2, 'Date');
		$worksheet->write(0, 3, 'Tweet');
		$worksheet->write(0, 4, 'Url');
		$worksheet->write(0, 5, 'Favs');
		$worksheet->write(0, 6, 'Retweets');
		
		$k = 1;
		foreach($input as $raw_tw) {
			$tw = explode('.', $raw_tw);
			$tweet_fields = $tweets[$tw[0]][$tw[1]];
			
			$worksheet->write($k, 0, $tweet_fields[7]);
			$worksheet->write($k, 1, '@' . $tweet_fields[2]);
			$worksheet->write($k, 2, $tweet_fields[0]);
			$worksheet->write($k, 3, $tweet_fields[3]);
			$worksheet->write($k, 4, 'https://twitter.com/' . $tw[0] . '/status/' . $tweet_fields[4]);
			$worksheet->write($k, 5, $tweet_fields[8]);
			$worksheet->write($k, 6, $tweet_fields[9]);
			
			$exported_tweets++;
			$k++;
		}
		
		$workbook->close();
?>
		<script language="javascript">alert("<?php echo $exported_tweets; ?> tweets have been exported into <?php echo addslashes(dirname(__FILE__)) . '/Exports/' . $account . '-' . date('Y-m-d') . '_' . date('H:i:s') . '.xls'; ?>");</script>
	
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
	if (sizeof($tweets) > 0) {
		foreach($tweets as $account => $content) {
			if (sizeof($content) > 0) {
?>
				<p><strong><?php echo 'Tweets: @' . $account; ?></strong> <a class="toggle" id="<?php echo 'tweets' . $account . 'toggle';?>" href="javascript:showhide('<?php echo 'tweets' . $account;?>');">+</a></p>
				<table class="sortable" id="<?php echo 'tweets' . $account;?>"><form action="excel_export.php" method="post" enctype="multipart/form-data">
				<tr><th><input type="checkbox" id="checkall_export_tweets_<?php echo utf8_encode($account);?>" onchange="checkAllElements('export_tweets_<?php echo utf8_encode($account);?>')"></th><th class="tweet-cell">Date</th><th>Tweet</th><th>Keywords</th><th>Favs</th><th>RT</th></tr>
<?php
				foreach($content as $tweet_id => $tweet) {
?>
					<tr><td><input type="checkbox" name="export_tweets_<?php echo utf8_encode($account);?>[]" value="<?php echo $account . '.' . $tweet[4]?>"></td><td class="left-col-tw"><?php echo tweetDateFormatTable($tweet[0]); ?></td><td class="left-col"><?php echo stripslashes($tweet[3]); ?></td><td><?php echo static_tweet_keywords_matches($tweet[3]); ?></td><td><?php echo $tweet[8]; ?></td><td><?php echo $tweet[9]; ?></td></tr>
<?php
				}
				
?>
				</table>
				<table class="sorttable-lit" id="<?php echo 'tweets' . $account . 'delete'; ?>">
				<tr><td class="button"><button type="submit" class="amp">Export</button></td></tr>
				</form></table>
<?php
				
			}
		}
	}
?>
<br>
<br>
</body>
</html>