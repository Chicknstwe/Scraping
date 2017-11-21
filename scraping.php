<?php require_once( dirname(__FILE__) . '/app/bootstrap.php' );

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Scraping tool</title> 
<link rel="stylesheet" type="text/css" href="/css/scraping.css" media="screen" />
<script src="/js/tools.js"></script>
<?php
	require_once('class/class.install.php');

	$web_progress = new Installer();
?>
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
<table class="table-fill">
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" id="apiform" target="progressFrame" name="scraping" method="post" enctype="multipart/form-data">
	<tr><th>Scraping tool</th></tr>
	<tr><td>Number of runs of the script <input type="range" name="exec_n_range" min="1" max="1000" value="50" step="1" id="n_exec" onchange="range_exec.value=value"><output align="center" id="range_exec">50</output></td></tr>
	<tr><td><input type="checkbox" id="checkall_selected_webs" onchange="checkAllElements('selected_webs')"> <strong>Select webs to perform scraping</strong><br />
<?php
	$s = 0;
	while($s < sizeof($websites)) {
?>
		<input type="checkbox" name="selected_webs[]" value="<?php echo $websites[$s];?>" /> <?php echo $names[$websites[$s]];?><br />	
<?php
		$s++;
	}
?>
	</td></tr>	
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
	<tr><td><input type="checkbox" name="save_imgs" value="Yes" > Save images<br>
    <input type="checkbox" name="save_docs" value="Yes"> Save documents<br>
	<input type="checkbox" name="show_info" value="Yes"> Show performance report</td></tr>
    <tr><td><input type="submit" name="apisubmit" id="apisubmit" class="amp" value="Run"/></td></tr>
</form>
</table>
<br><br>
<br>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  extract($_POST);
  $n_exec = $_REQUEST["exec_n_range"];
  if(isset($_REQUEST["selected_keywords"])) {
		$selected_keywords = $_REQUEST["selected_keywords"];
  } else {
	  $selected_keywords = array();
  }
  if(isset($_REQUEST['selected_webs'])) {
		$sel_websites = $_REQUEST['selected_webs'];
  } else {
	  $sel_websites = array();
  }
  
  
  
  
if (isConnected() && sizeof($selected_keywords) > 0 && sizeof($sel_websites) > 0) {	
	$media_ext = array("jpeg", ".jpg", ".png");
	$compo_url = array("/", "?", "&", "#");
	$docs_ext = array(".pdf", ".doc", "docx", ".xls", ".odt", ".ods", ".rtf", ".dot", "dotx", ".txt", ".xml", "docm", "dotm", ".dic", ".rar", ".zip", "r.gz", ".tar", ".bz2");

?>
<script language="javascript">alert("The script has been executed.");</script>
<?php

	$web_progress->placeholder();
	$web_progress->setLogPath(''); 

	$array_output = array();
	$img_num=0;
	$docs_count=0;
	$comp_count=0;
	$url_count=0;
	$added_count=0;
	$new_url_count=0;
	$i=0;
	$q=0;
	$path = "matches";
	$key_matches=0;
	$save_img_test=0;
	$save_doc_test=0;
	$ejecuciones=0;
	
	$web_progress->setSteps(sizeof($sel_websites) * $n_exec);
	$web_progress->defineBar('#000000', '#000000');
		
while ($q < sizeof($sel_websites)) {
	 $on_websites = array();
	 $web_name = $names[$sel_websites[$q]];
	 array_push($on_websites, $sel_websites[$q]);
	 $k=0;
	 while ($k < sizeof($on_websites) && $k < $n_exec) {
			
		$base = $sel_websites[$q];
		$web = $on_websites[$k];
		if(substr($web, 0, 2) == '//') {
			$web = 'http:' . $web;
			$comp_count++;
		}
		$output = file_get_contents_curl($web);
		$var = pc_link_extractor($output);
		$media_output = array();
		$docs_output = array();
		$url_count++;
		

		
		$i=0;
		while ($i < sizeof($var)) {
			if(substr($var[$i][0], 0, 2) == '//') {
				$var[$i][0] = 'http:' . $var[$i][0];
				$comp_count++;
			} elseif (in_array(substr($var[$i][0], 0, 1), $compo_url)) {
				$var[$i][0] = $base  . "" . $var[$i][0];
				$comp_count++;
			}
			if (substr($var[$i][0], 0, strlen($base)) == $base) {
				$var[$i][0] = trim($var[$i][0]);
				$var[$i][1] = trim($var[$i][1]);
				$var[$i][1] = tag_img_extractor($var[$i][1]);
				
				if (in_array(substr($var[$i][1], -4, 4), $media_ext)) {
					if(substr($var[$i][1], 0, 2) == '//') {
						$var[$i][1] = 'http:' . $var[$i][1];
						$comp_count++;
					}
					if (!in_array($var[$i][1], $img_added)) {
						array_push($media_output, $var[$i][1]);
						array_push($img_added, $var[$i][1]);
					}
					$img_num++;
				} 
				
				if (in_array(substr($var[$i][0], -4, 4), $media_ext)) {	
					if(substr($var[$i][0], 0, 2) == '//') {
						$var[$i][0] = 'http:' . $var[$i][0];
						$comp_count++;
					}
					if (!in_array($var[$i][0], $img_added)) {
						array_push($media_output, $var[$i][0]);
						array_push($img_added, $var[$i][0]);
					}
					$img_num++;
				} elseif (in_array(substr($var[$i][0], -4, 4), $docs_ext)) {
					if(substr($var[$i][0], 0, 2) == '//') {
						$var[$i][0] = 'http:' . $var[$i][0];
						$comp_count++;
					}
					if (!in_array($var[$i][0], $docs_added)) {
						array_push($docs_output, $var[$i][0]);
						array_push($docs_added, $var[$i][0]);
					}
					$docs_count++;
				} elseif (!in_array($var[$i][0], $on_websites)) {
					array_push($on_websites, $var[$i][0]);
					if (!in_array($var[$i][0], $added)) {
						array_push($added, $var[$i][0]);
						$added_count++;
						if (!in_array($var[$i][0], $websites)) {
							array_push($array_output, $var[$i][0]);
							$new_url_count++;
						}
					}
				}
			} elseif (in_array(substr($var[$i][0], -4, 4), $docs_ext)) {
				if(substr($var[$i][0], 0, 2) == '//') {
					$var[$i][0] = 'http:' . $var[$i][0];
					$comp_count++;
				}
				if (!in_array($var[$i][0], $docs_added)) {
					array_push($docs_output, $var[$i][0]);
					array_push($docs_added, $var[$i][0]);
				}
				$docs_count++;
			} elseif (in_array(substr($var[$i][0], -4, 4), $media_ext)) {
				if(substr($var[$i][0], 0, 2) == '//') {
					$var[$i][0] = 'http:' . $var[$i][0];
					$comp_count++;
				}			
				if (!in_array($var[$i][0], $img_added)) {
					array_push($media_output, $var[$i][0]);
					array_push($img_added, $var[$i][0]);
				}
				$img_num++;
				}
				
			addScrapingReg($added, $img_added, $docs_added);
			$i++;
		}

		$i=0;
		while ($i < sizeof($selected_keywords)) {
			if (strpos($output, $selected_keywords[$i]) !== false) {
				$path_base = $path . "/" . modSpace($web_name);
				if (!file_exists($path)) mkdir($path, 0777, true);
				if (!file_exists($path_base)) mkdir($path_base, 0777, true);
				$scrapped = fopen($path . '/' . modSpace($web_name) . '/' . valid_chars($on_websites[$k]) . '.html', "w") or die("¡Error opening " . $path_base . "/" . basename(modSpace($on_websites[$k])) . ".html!");
				fwrite($scrapped, $output);
				fclose($scrapped);
				$key_matches++;
				if (isset($_POST['save_imgs']) && $_POST['save_imgs'] == 'Yes') {
					$imgs = array_tag_img_extractor($output);
					$z=0;
					
					
					while ($z < sizeof($imgs)) {
						if (in_array(substr($imgs[$z], -4, 4), $media_ext)) {
							array_push($media_output, $imgs[$z]);
						}
						$z++;
					}
					$z=0;
					while ($z < sizeof($media_output)) {
						if(substr($media_output[$z], 0, 2) == '//') {
							$media_output[$z] = 'http:' . $media_output[$z];
							$comp_count++;
						}
						$img_file = file_get_contents_curl($media_output[$z]);
						$media_path = $path_base . "/imgs";
						if (!file_exists($path)) mkdir($path, 0777, true);
						if (!file_exists($path_base)) mkdir($path_base, 0777, true);
						if (!file_exists($media_path)) mkdir($media_path, 0777, true);
						$get_img = fopen($media_path . "/" . valid_chars(basename(modSpace($media_output[$z]))), "w") or die("¡Error opening " . $media_path . "/" . basename(modSpace($media_output[$z])));
						fwrite($get_img, $img_file);
						fclose($get_img);
						$save_img_test++;
						$z++;
					}
				}
				if (isset($_POST['save_docs']) && $_POST['save_docs'] == 'Yes') {
					$z=0;
					while ($z < sizeof($docs_output)) {
						if(substr($docs_output[$z], 0, 2) == '//') {
							$docs_output[$z] = 'http:' . $docs_output[$z];
							$comp_count++;
						}
						$doc_file = file_get_contents_curl($docs_output[$z]);
						$docs_path = $path_base . "/docs";
						if (!file_exists($path)) mkdir($path, 0777, true);
						if (!file_exists($path_base)) mkdir($path_base, 0777, true);
						if (!file_exists($docs_path)) mkdir($docs_path, 0777, true);
						$get_doc = fopen($docs_path . "/" . valid_chars(basename(modSpace($docs_output[$z]))), "w") or die("¡Error opening " . $docs_path . "/" . basename(modSpace($docs_output[$z])));
						fwrite($get_doc, $doc_file);
						fclose($get_doc);
						$save_doc_test++;
						$z++;
					}
				}			
				break;
			} 
			$i++;
		}
		$k++;
		 $bar_msg = 'Scraping ' . $web_name;
		 $web_progress->save($bar_msg);
		$web_progress->delay(0.0);
		$ejecuciones++;
	}
	$k=0;
	$q++;
}
$web_progress->clearTemp(); 
?>

<?php

	if (isset($_POST['show_info']) && $_POST['show_info'] == 'Yes') {
?>
		<br><br><br><table class="table-fill"><tr><th colspan=2>Performance report</th></tr>
		<tr><td>Urls processed</td><td><?php echo $url_count; ?></td></tr>
		<tr><td>New url processed</td><td><?php echo $new_url_count; ?></td></tr>
		<tr><td>Matches and scrapped urls</td><td><?php echo $key_matches; ?></td></tr>
		<tr><td>Stored images</td><td><?php echo $save_img_test; ?></td></tr>
		<tr><td>Stored documents</td><td><?php echo $save_doc_test; ?></td></tr>
		<tr><td>Urls added to registry</td><td><?php echo $added_count; ?></td></tr>
		<tr><td>Composed urls</td><td><?php echo $comp_count; ?></td></tr>
		<tr><td>Script runs</td><td><?php echo $ejecuciones; ?></td></tr>
		</table><br><br><br>
<?php
	}
} else {
?>
	<script language="javascript">alert("You are not connected to the onternet or you have not chosen any web or word.");</script>
<?php
	} 
}
?>
</body>
</html>
