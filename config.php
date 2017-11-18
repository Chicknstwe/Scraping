<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' ); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Settings</title> 
<link rel="stylesheet" type="text/css" href="/css/config.css" media="screen" />
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

$output = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
	$boton = $_REQUEST["boton"];
	switch($boton) {
		
		case 'webs':
		  $url = $_REQUEST["web_new_url"];
		  $name = $_REQUEST["web_new_name"];

		  
		  if (isset($_POST['url_select'])) {
			$web_select = $_POST['url_select'];
		  }
		  
		  if ($_REQUEST["web_new_url"] !== "" || $_REQUEST["web_new_name"] !== "") {
			  if ($_REQUEST["web_new_url"] == "" || $_REQUEST["web_new_name"] == "") {
				  $output = "You must write the name and the adress to add a new web.";
			  } else {
				  if(substr($url, 0, 4) != 'http') {
					  $url = 'http://' . $url;
				  }
				  $exists = false;
				  $size = sizeof($websites);
				  $i=0;
				  while ($i < $size) {
					  if ($url == $websites[$i] || $name == $names[$websites[$i]]) {
						  $exists = true;
					  }
					  $i++;
				  }
				  if (!$exists) {
					  array_push($websites, $url);
					  $names[$url] = $name;
					  addResources($websites, $names, $keywords, $twitter_accs);
					  if ($websites[sizeof($websites) - 1] == $url &&  $names[$websites[sizeof($websites) - 1]] == $name) {
						  $output = "The new web has been added.";
					  } else {
						  $output = "The name or url has not been added.";
					  }	  
					  

				  } else {
					  $output = "The name or the url is already in the registers..";
				  }
			  }
		  } elseif ($web_select > -1 && $web_select !== '') {
			  $delete_web_test = $websites[$web_select];
			  $delete_name_test = $names[$websites[$web_select]];
			  unset($names[$websites[$web_select]]);
			  array_splice($websites, $web_select, 1);
			  addResources($websites, $names, $keywords, $twitter_accs);
			  if (sizeof($websites) > $web_select) {
				  if ($websites[$web_select] != $delete_web_test && $names[$websites[$web_select]] != $delete_name_test) {
					  $output = "The website has been deleted successfully.";
				  } else {
					  $output = "The website has not been deleted properly. A reset of the resources registers may be necessary. Alternatively you can edit them in app/libs/resources.inc.php.";
				  }
			  } else {
				  $output = "The web has been deleted successfully.";
			  }
		  }
		  break;
		  
		case 'scrap_reg_reset':
		  scrapRegReset();
		  $output = "Web scraping registers have been reset.";
		  break;
		
		case 'twitter_reg_reset':
		  twitterRegReset();
		  $output = "Twitter registers have been reset.";
		  break;
		  
		case 'resources_reg_reset':
		  resRegReset();
		  $output = "Websites, twitter acounts and keywords have been reset.";
		  break;
		
		case 'twitter_oauth':
		  if(isset($_REQUEST["twitter_oauth"]) && isset($_REQUEST["twitter_oauth_secret"]) && isset($_REQUEST["twitter_consumer"]) && isset($_REQUEST["twitter_consumer_secret"])) {
			  $new_oauth = $_REQUEST["twitter_oauth"];
			  $new_oauth_secret = $_REQUEST["twitter_oauth_secret"];
			  $new_consumer = $_REQUEST["twitter_consumer"];
			  $new_consumer_secret = $_REQUEST["twitter_consumer_secret"];
			  addTwitterDevCredentials($new_oauth, $new_oauth_secret, $new_consumer, $new_consumer_secret);
			  $output = "Your new twitter dev credentials have been saved.";
		  }
		  break;

		case 'twitter':
		  $twitter = $_REQUEST["twitter_new"];
		  $twitter_select = '';

		  if (isset($_POST['twitter_select'])) {
			$twitter_select = $_POST['twitter_select'];
		  }
		  
		  if ($_REQUEST["twitter_new"] != "") {
				  if (!array_key_exists($twitter, $tweets)) {
					  $tweets[$twitter] = array();
					  array_push($twitter_accs, $twitter);
					  addTwitterReg($tweets, $twitter_reg);
					  addResources($websites, $names, $keywords, $twitter_accs);
					  if (in_array($twitter, array_keys($tweets))) {
						  $output = "The account has been added successfully.";
					  } else {
						  $output = "The account has not been added.";
					  }	  
				  } else {
					  $output = "The account is already in the register.";
				  }
		  } elseif ($twitter_select > -1 && $twitter_select !== '') {
			  array_splice($tweets, $twitter_select, 1);
			  array_splice($twitter_accs, $twitter_select, 1);
			  addTwitterReg($tweets, $twitter_reg);
			  addResources($websites, $names, $keywords, $twitter_accs);
			  if (!array_key_exists($twitter_select, $tweets)) {
				  $output = "The account has been deleted successfully.";
			  } else {
				  $output = "The account has not been deleted.";
			  }
		  }
		  break;
		  
		case 'keywords':
		  $keyword = $_REQUEST["keyword_new"];
		  $keyword_select = '';

		  if (isset($_POST['keyword_select'])) {
			$keyword_select = $_POST['keyword_select'];
		  }
		  
		  if ($_REQUEST["keyword_new"] !== "") {
				  $exists = false;
				  $size = sizeof($keywords);
				  $i=0;
				  while ($i < $size) {
					  if ($keyword == $keywords[$i]) {
						  $exists = true;
					  }
					  $i++;
				  }
				  if (!$exists) {
					  array_push($keywords, $keyword);
					  addResources($websites, $names, $keywords, $twitter_accs);
					  if ($keywords[sizeof($keywords) - 1] == $keyword) {
						  $output = "The keyword has been added successfully.";
					  } else {
						  $output = "The keyword has not been added.";
					  }	  
					  

				  } else {
					  $output = "The keyword is already in the register.";
				  }
		  } elseif ($keyword_select > -1 && $keyword_select !== '') {
			  $delete_test = $keywords[$keyword_select];
			  array_splice($keywords, $keyword_select, 1);
			  addResources($websites, $names, $keywords, $twitter_accs);
			  if (sizeof($keywords) > $keyword_select) {
				  if ($keywords[$keyword_select] != $delete_test) {
					  $output = "The keyword has been deleted successfully.";
				  } else {
					  $output = "The keyword has not been deleted.";
				  }
			  } else {
				  $output = "The keyword has been deleted successfully.";
			  }
		  }
		  break;
	}
}
?>
<br><br><br><table class="table-fill"><tr><th colspan=4>Settings</th></tr>
<tr><form action="config.php" method="post" enctype="multipart/form-data"><td>
	Add web</td>
	<td><input type="text" placeholder="Name" name="web_new_name" /><br>
	<input type="text" placeholder="Url" name="web_new_url" /></td>
	<td colspan=2>Be sure to write name and url in the correct field. Urls must start with http:// or https://.</td>
	</tr>
	

	<tr><td>Delete web</td>
	<td><select name="url_select" size="1">
    <option value="Ninguna" selected>None</option>
<?php	
	$size=sizeof($websites);
	$i=0;
	while ($i < $size) {
		if (isset($websites[$i])) {
?>
			<option value="<?php echo $i; ?>"><?php echo $names[$websites[$i]] . " (" . $websites[$i]; ?>)</option>
<?php
		}
		$i++;
	}
?>
    </select></td>
	

	<td colspan=2>The website will be deleted from register.</td></tr>
    <tr><td></td><td class="subm" colspan=3><button class="amp" type="submit" name="boton" value="webs">Apply</button></td></tr>
</form>
<tr><td colspan=4></td></tr>
<tr><form action="config.php" method="post" enctype="multipart/form-data"><td>

	Add keyword</td><td colspan=3><input placeholder="Keyword" type="text" name="keyword_new" /></td></tr>
	

	<tr><td>Delete keyword </td>
	<td><select name="keyword_select" size="1">
    <option value="Ninguna" selected>None</option>
<?php	
	$size=sizeof($keywords);
	$i=0;
	while ($i < $size) {
		if (isset($keywords[$i])) {
?>
			<option value="<?php echo $i; ?>"><?php echo urldecode($keywords[$i]); ?></option>
<?php	
		}
		$i++;
	}
?>
    </select></td>
	

	<td class="info" colspan=2>The keyword will be deleted from register.</td></tr>
    <tr><td></td><td class="subm" colspan=3><button type="submit" class="amp" name="boton" value="keywords">Apply</button></td></tr>
</form>
<tr><td colspan=4></td></tr>
<tr><form action="config.php" method="post" enctype="multipart/form-data">
<td>Oauth access token</td><td colspan=3><input type="text" name="twitter_oauth" size="70" value="<?php echo $oauth_access_token;?>"/></td></tr>
<tr><td>Oauth access token secret</td><td colspan=3><input type="text" name="twitter_oauth_secret" size="70" value="<?php echo $oauth_access_token_secret;?>"/></td></tr>
<tr><td>Consumer key</td><td colspan=3><input type="text" name="twitter_consumer" size="70" value="<?php echo $consumer_key;?>"/></td></tr>
<tr><td>Consumer secret</td><td colspan=3><input type="text" name="twitter_consumer_secret" size="70" value="<?php echo $consumer_secret;?>"/></td></tr>
<tr><td></td><td class="subm" colspan=3><button type="submit" class="amp" name="boton" value="twitter_oauth">Apply</button></td></form></tr>
<tr><td colspan=4></td></tr>
<tr><form action="config.php" method="post" enctype="multipart/form-data"><td>

	Add twitter account</td><td colspan=3><input placeholder="Without @" type="text" name="twitter_new" /></td>
	

	<tr><td>Delete twitter account </td>
	<td><select name="twitter_select" size="1">
    <option value="Ninguna" selected>None</option>
	
<?php	
	$size=sizeof($twitter_accs);
	$i=0;
	while ($i < $size) {
		if (isset($twitter_accs[$i])) {
?>
			<option value="<?php echo $i;?>">@<?php echo urldecode($twitter_accs[$i]);?></option>
<?php
		}
		$i++;
	}
?>	
    </select></td>
	

	<td class="info" colspan=2>The account will be deleted from register.</td></tr>
    <tr><td></td><td class="subm" colspan=3><button type="submit" class="amp" name="boton" value="twitter">Apply</button></td></tr>
</form>


<tr></tr>



<tr><td colspan=2><form action="config.php" method="post" enctype="multipart/form-data">
    <button type="submit" class="amp" name="boton" value="scrap_reg_reset">Reset scraping registers</button>
</form></td><td colspan=2>This will delete all registers used by the script to know which urls, images and documents have already been processed.</td></tr>

<tr><td colspan=2><form action="config.php" method="post" enctype="multipart/form-data">
    <button type="submit" class="amp" name="boton" value="twitter_reg_reset">Reset twitter registers</button>
</form></td><td colspan=2>This will delete all registers used by the script to know which tweets have already been processed.</td></tr>
<tr><td colspan=2><form action="config.php" method="post" enctype="multipart/form-data">
    <button type="submit" class="amp" name="boton" value="resources_reg_reset">Reset resources registers</button>
</form></td><td colspan=2>This will delete all websites, twitter accounts and keywords stored.</td></tr>
</table>
<br><br><br><br>
<?php
if(strlen($output) > 5) {
?>	
	<script language="javascript">alert("<?php echo $output; ?>");</script>
<?php
}
?>
</body>
</html>
