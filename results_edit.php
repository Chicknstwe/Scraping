<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' ); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Results edit</title>
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
	$button = $_REQUEST["delete_button"];
	$button = explode('.', $button);
	
	if($button[0] == 'webs') {
		$request = "edit_webs_" . $button[1];
		if(isset($_REQUEST[$request])) { 
			$input = $_REQUEST[$request];
			
			foreach($input as $file) {
				deleteFile($file);
			}
		}
		if(isset($_REQUEST[$request . "_imgs"])) { 
			$imgs_input = $_REQUEST[$request . "_imgs"];
			
			foreach($imgs_input as $file) {
				deleteFile($file);
			}
		}
		if(isset($_REQUEST[$request . "_docs"])) { 
			$docs_input = $_REQUEST[$request . "_docs"];
			foreach($docs_input as $file) {
				deleteFile($file);
			}
		}

?>
		<META HTTP-EQUIV="Refresh" Content="0; URL=?results=webs">
<?php
	} elseif ($button[0] == 'tweets') {
		$request = "edit_tweets_" . $button[1];
		if(isset($_REQUEST[$request])) {
			$input = $_REQUEST[$request];
		}
		foreach($input as $raw_tw) {
			$tw = explode('.', $raw_tw);
			unset($tweets[$tw[0]][$tw[1]]);
			array_splice($twitter_reg[$tw[0]], $tw[1], 1);
		}
		addTwitterReg($tweets, $twitter_reg);
?>
		<META HTTP-EQUIV="Refresh" Content="0; URL=?results=tweets">
<?php
	}
}
?>
<br>
<br>
<br>
<?php
	if(isset($_GET['results'])) {
		$results = $_GET['results'];
	} else {
		$results = '';
	}
	if($results == 'webs') {	
		$root="matches";
		$open_root=opendir($root);
		while ($categoria = readdir($open_root)) {
			if($categoria!="." AND $categoria!="..") {
				$path="matches/" . $categoria . "";
				$carpeta=opendir($path);
				if(sizeof(scandir($path)) > 4) {
?>
					<p><strong><?php echo 'Web: '. utf8_encode($categoria); ?></strong> <a class="toggle" id="<?php echo utf8_encode($categoria) . 'toggle'; ?>" href="javascript:showhide('<?php echo utf8_encode($categoria); ?>');">+</a></p>
					<table class="sortable" id="<?php echo utf8_encode($categoria); ?>"><form action="results_edit.php" method="post" enctype="multipart/form-data">
					<tr><th><input type="checkbox" id="checkall_edit_webs_<?php echo utf8_encode($categoria);?>" onchange="checkAllElements('edit_webs_<?php echo utf8_encode($categoria);?>')"></th><th>File</th><th>Keywords</th></tr>
<?php
					while ($archivo = readdir($carpeta)) {
						if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
							$url = ucfirst(urldecode($archivo));
?>
							<tr><td><input type="checkbox" name="edit_webs_<?php echo utf8_encode($categoria);?>[]" value="matches/<?php echo $categoria . "/" . $archivo; ?>"></td>
							<td class="left-col"><font><?php echo $url; ?></font></td>
							<td width="160"><?php echo static_keywords_matches("./matches/" . $categoria . "/" . $archivo . ""); ?></td>
							</tr>
<?php
						}
					}
?>
					</table>
<?php
				}
				$name=utf8_encode($categoria);
				$img_path="matches/" . $categoria . "/imgs";
				if (file_exists($img_path) && sizeof(scandir($img_path)) > 2) {
?>
					<table class="sortable" id="<?php echo $name . "img"; ?>"><tr bgcolor="00a699" width="200">
					<th><input type="checkbox" id="checkall_edit_webs_<?php echo utf8_encode($categoria);?>_imgs" onchange="checkAllElements('edit_webs_<?php echo utf8_encode($categoria);?>_imgs')"></th><th colspan=3 align="center"><font><strong><?php echo $name . " - Images"; ?></strong></font></th>
<?php
					$carpeta=opendir($img_path);
					while ($archivo = readdir($carpeta)) {
						if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
							$url = ucfirst(urldecode($archivo));
?>
							<tr><td><input type="checkbox" name="edit_webs_<?php echo utf8_encode($categoria);?>_imgs[]" value="matches/<?php echo $categoria . "/imgs/" . $archivo; ?>"></td>
							<td class="left-col"><font><?php echo $url; ?></font></td>
							</tr>
<?php
						}
					}
?>
					</table>
<?php
				}
				$doc_path="matches/" . $categoria . "/docs";
				if (file_exists($doc_path) && sizeof(scandir($doc_path)) > 2) {
?>
					<table class="sortable" id="<?php echo $name . "docs"; ?>"><tr bgcolor="00a699" width="200">
					<th><input type="checkbox" id="checkall_edit_webs_<?php echo utf8_encode($categoria);?>_docs" onchange="checkAllElements('edit_webs_<?php echo utf8_encode($categoria);?>_docs')"></th><th colspan=3><font><strong><?php echo $name . " - Documents"; ?></strong></font></th>
<?php
					$carpeta=opendir($doc_path);
					while ($archivo = readdir($carpeta)) {
						if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
							$url = ucfirst(urldecode($archivo));
?>
							<tr class="left-col"><td><input type="checkbox" name="edit_webs_<?php echo utf8_encode($categoria);?>_docs[]" value="matches/<?php echo $categoria . "/docs/" . $archivo; ?>"></td>
							<td width="400"><font><?php echo $url; ?></font></td>
							</tr>
<?php
						}
					}
?>
					</table>
<?php				
				}
				closedir($carpeta);
			}
			
			if($categoria!="." AND $categoria!="..") {
?>
				<table class="sorttable-lit" id="<?php echo $name . 'delete'; ?>">
				<tr><td class="button"><button type="submit" class="amp"  name="delete_button" value="webs.<?php echo utf8_encode($categoria);?>">Delete</button></td></tr>
				</form>
				</table>
			
<?php
			}
		}
		
		closedir($open_root);
	} elseif($results == 'tweets') {
	
		if (sizeof($tweets) > 0) {
			foreach($tweets as $account => $content) {
				if (sizeof($content) > 0) {
?>
					<p><strong><?php echo 'Tweets: @' . $account; ?></strong> <a class="toggle" id="<?php echo 'tweets' . $account . 'toggle';?>" href="javascript:showhide('<?php echo 'tweets' . $account;?>');">+</a></p>
					<table class="sortable" id="<?php echo 'tweets' . $account;?>"><form action="results_edit.php" method="post" enctype="multipart/form-data">
					<tr><th><input type="checkbox" id="checkall_edit_tweets_<?php echo utf8_encode($account);?>" onchange="checkAllElements('edit_tweets_<?php echo utf8_encode($account);?>')"></th><th class="tweet-cell">Date</th><th>Tweet</th><th>Keywords</th><th>Favs</th><th>RT</th></tr>
<?php
					foreach($content as $tweet_id => $tweet) {
?>
						<tr><td><input type="checkbox" name="edit_tweets_<?php echo utf8_encode($account);?>[]" value="<?php echo $account . '.' . $tweet[4]?>"></td><td class="left-col-tw"><?php echo tweetDateFormatTable($tweet[0]); ?></td><td class="left-col"><?php echo stripslashes($tweet[3]); ?></td><td><?php echo static_tweet_keywords_matches($tweet[3]); ?></td><td><?php echo $tweet[8]; ?></td><td><?php echo $tweet[9]; ?></td></tr>
<?php
					}
					
?>
					</table>
					<table class="sorttable-lit" id="<?php echo 'tweets' . $account . 'delete'; ?>">
					<tr><td class="button"><button type="submit" class="amp"  name="delete_button" value="tweets.<?php echo utf8_encode($account); ?>">Delete</button></td></tr>
					</form></table>
<?php
					
				}
			}
		}
	}
?>
<br>
<br>
</body>
</html>