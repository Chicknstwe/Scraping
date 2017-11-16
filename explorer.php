<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' ); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Explorer</title> 
<script src="/js/sorttable.js"></script>
<script src="/js/tools.js"></script>
<link rel="stylesheet" type="text/css" href="/css/explorer.css" media="screen" />
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
<br>
<br>
<br>
<?php
	
	$root="matches";
	$open_root=opendir($root);
	while ($categoria = readdir($open_root)) {
		if($categoria!="." AND $categoria!="..") {
			$path="matches/" . $categoria . "";
			$carpeta=opendir($path);
			if(sizeof(scandir($path)) > 4) {
?>
				<p><strong><?php echo 'Web: '. utf8_encode($categoria); ?></strong> <a class="toggle" id="<?php echo utf8_encode($categoria) . 'toggle'; ?>" href="javascript:showhide('<?php echo utf8_encode($categoria); ?>');">+</a></p>
				<table class="sortable" id="<?php echo utf8_encode($categoria); ?>">
				<tr><th>File</th><th>Keywords</th><th>Link</th></tr>
<?php
				while ($archivo = readdir($carpeta)) {
					if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
						$url = ucfirst(urldecode($archivo));
?>
						<tr><td class="left-col"><font><?php echo $url; ?></font></td>
						<td width="160"><?php echo static_keywords_matches("./matches/" . $categoria . "/" . $archivo . ""); ?></td>
						<td width="100"><a href="matches/<?php echo $categoria . "/" . $archivo; ?>">Link</a></td>
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
				<th colspan=3 align="center"><font><strong><?php echo $name . " - Images"; ?></strong></font></th>
<?php
				$carpeta=opendir($img_path);
				while ($archivo = readdir($carpeta)) {
					if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
						$url = ucfirst(urldecode($archivo));
?>
						<tr><td class="left-col"><font><?php echo $url; ?></font></td>
						<td width="260" colspan=2 align="center"><a href="matches<?php echo $categoria . "/imgs/" . $archivo; ?>">Link</a></td>
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
				<th colspan=3><font><strong><?php echo $name . " - Documents"; ?></strong></font></th>
<?php
				$carpeta=opendir($doc_path);
				while ($archivo = readdir($carpeta)) {
					if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
						$url = ucfirst(urldecode($archivo));
?>
						<tr class="left-col"><td width="400"><font><?php echo $url; ?></font></td>
						<td width="260" colspan=2 align="center"><a href="matches/<?php echo $categoria . "/docs/" . $archivo?>">Link</a></td>
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
	}
	
	closedir($open_root);
	if (sizeof($tweets) > 0) {
		foreach($tweets as $account => $content) {
			if (sizeof($content) > 0) {
?>
				<p><strong><?php echo 'Tweets: @' . $account; ?></strong> <a class="toggle" id="<?php echo 'tweets' . $account . 'toggle';?>" href="javascript:showhide('<?php echo 'tweets' . $account;?>');">+</a></p>
				<table class="sortable" id="<?php echo 'tweets' . $account;?>">
				<tr><th class="tweet-cell">Date</th><th>Image</th><th>Tweet</th><th>Keywords</th><th>Link</th><th>Entities</th><th>Favs</th><th>RT</th></tr>
<?php
				foreach($content as $tweet_id => $tweet) {
?>
					<tr><td class="left-col-tw"><?php echo tweetDateFormatTable($tweet[0]); ?></td><td><?php echo $tweet[1]; ?></td><td class="left-col"><?php echo stripslashes($tweet[3]); ?></td><td><?php echo static_tweet_keywords_matches($tweet[3]); ?></td><td> <a href="https://twitter.com/<?php echo $account . '/status/' . $tweet_id; ?>">Enlace</a></td><td>
<?php
					$c = 1;
					foreach($tweet[10] as $media_url) {
?>
						<a href="<?php echo $media_url; ?>">Entity-<?php echo $c; ?></a>
<?php					
						$c++;
					}
?>			
					</td><td><?php echo $tweet[8]; ?></td>
					<td><?php echo $tweet[9]; ?></td></tr>
<?php
				}
?>
				</table>
<?php
				
			}
		}
	}
?>
<br>
<br>
</body>