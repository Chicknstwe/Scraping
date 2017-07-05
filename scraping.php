<!--

MIT License

Copyright (c) 2017 Demetrio Carmona Derqui

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Scraping Beta 0.4 - Scraping</title> <!-- Código hecho 100% por Demetrio Carmona Derqui. Imágenes de freepik.com libres de licencias. Estilos a partid de fonts.googleapis.com. -->
</head>
<style type="text/css">
  
@import url(http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100);

body {
  background-color: #00a699;
  font-family: "Roboto", helvetica, arial, sans-serif;
  font-size: 16px;
  font-weight: 400;
  text-rendering: optimizeLegibility;
}

div.table-title {
  display: block;
  margin: auto;
  max-width: 600px;
  padding:5px;
  width: 100%;
}

.table-fill {
  background: white;
  border-radius:3px;
  border-collapse: collapse;
  height: 320px;
  margin: auto;
  max-width: 600px;
  padding:5px;
  width: 100%;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  animation: float 5s infinite;
}
 
th {
  color:#D5DDE5;;
  background:#1b1e24;
  border-bottom:4px solid #9ea7af;
  border-right: 1px solid #343a45;
  font-size:23px;
  font-weight: 100;
  padding:24px;
  text-align:left;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  vertical-align:middle;
}

th:first-child {
  border-top-left-radius:3px;
}
 
th:last-child {
  border-top-right-radius:3px;
  border-right:none;
}
  
tr {
  border-top: 1px solid #C1C3D1;
  border-bottom-: 1px solid #C1C3D1;
  color:#666B85;
  font-size:16px;
  font-weight:normal;
  text-shadow: 0 1px 1px rgba(256, 256, 256, 0.1);
}

div.advert {
  font-size:16px;
}
 
tr:hover td {
  background:#4E5066;
  color:#FFFFFF;
  border-top: 1px solid #22262e;
  border-bottom: 1px solid #22262e;
}

td:hover h5 {
  background:#4E5066;
  color:#FFFFFF;
}
 
tr:first-child {
  border-top:none;
}

tr.output  {
  border-bottom:none;
}
tr:last-child {
  border-bottom:none;
}
 
tr:nth-child(odd) td {
  background:#EBEBEB;
}
 
tr:nth-child(odd):hover td {
  background:#4E5066;
}

tr:last-child td:first-child {
  border-bottom-left-radius:3px;
}
 
tr:last-child td:last-child {
  border-bottom-right-radius:3px;
}
 
td {
  background:#FFFFFF;
  padding:20px;
  text-align:left;
  vertical-align:middle;
  font-weight:300;
  font-size:18px;
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
  border-right: 1px solid #C1C3D1;
}

td.button {
  background:#FFFFFF;
  padding:0px;
  text-align:middle;
  vertical-align:middle;
  font-weight:300;
  font-size:18px;
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
  border-right: 1px solid #C1C3D1;
}

input.calc {
    width: 26em;  
	height: 5em;
	font-size:18px;
}

input.amp {
    width: 31em;  
	height: 4em;
	font-size:18px;
}

input.reset {
    width: 11em;  
	height: 5em;
	font-size:18px;
}

input.reset1 {
    width: 13em;  
	height: 5em;
	font-size:18px;
}

input.loot {
    width: 34em;  
	height: 4em;
	font-size:18px;
}

h5 {
  background:#EBEBEB;
  padding:1px;
  text-align:right;
  vertical-align:middle;
  font-weight:300;
  font-size:40px;
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
  border-right: 1px solid #C1C3D1;
}

td:last-child {
  border-right: 0px;
}

td.left-col {
	word-wrap: break-word;
	max-width: 500px;
	min-width: 500px;
}

</style>
<br><br><br><table class="table-fill">
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
	<tr><th>Parámetros de ejecución</th></tr>
	<tr><td>Nº de ejecuciones del script <input type="range" name="exec_n_range" min="1" max="5000" value="50" step="1" id="n_exec" onchange="range_exec.value=value"><output align="center" id="range_exec">50</output></td></tr>
	<tr><td><input type="checkbox" name="save_imgs" value="Yes" > Guardar imágenes<br>
    <input type="checkbox" name="save_docs" value="Yes"> Guardar documentos<br>
	<input type="checkbox" name="show_info" value="Yes"> Mostrar informe tras ejecución</td></tr>
    <tr><td><input type="submit" name="submit" class="amp" value="Ejecutar scraping"/></td></tr>
</form>
</table>
<br><br>
<br>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  extract($_POST);
  $n_exec = $_REQUEST["exec_n_range"];
  
include 'resources.php';
include 'functions.php';

if (isConnected()) {	
	$media_ext = array("jpeg", ".jpg", ".png");
	$compo_url = array("/", "?", "&", "#");
	$docs_ext = array(".pdf", ".doc", "docx", ".xls", ".odt", ".ods", ".rtf", ".dot", "dotx", ".txt", ".xml", "docm", "dotm", ".dic", ".rar", ".zip", "r.gz", ".tar", ".bz2");

echo '<script language="javascript">alert("El scraping se ha ejecutando.");</script>';

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
	
	// Quitar estos // y este texto para hacer que el valor 0 en ejecuciones haga ejecutarse el script hasta analizar toda la web.
	// Precaución: puede tardar demasiado en ejecutarse, o ejecutarse de forma infinita.
	//if ($n_exec == 0) {
	//	$n_exec = 9999999;
	//}
		
while ($q < sizeof($websites)) {
	 $on_websites = array();
	 array_push($on_websites, $websites[$q]);
	 $k=0;
	 while ($k < sizeof($on_websites) && $k < $n_exec) {
			
		$base = $websites[$q];
		$web = $on_websites[$k];
		$output = file_get_contents_curl($web);
		$var = pc_link_extractor($output);
		$media_output = array();
		$docs_output = array();
		$url_count++;
		
		$i=0;
		while ($i < sizeof($var)) {

			if (in_array(substr($var[$i][0], 0, 1), $compo_url)) {
				$var[$i][0] = $base  . "" . $var[$i][0];
				$comp_count++;
			}
			if (substr($var[$i][0], 0, strlen($base)) == $base) {
				$var[$i][0] = trim($var[$i][0]);
				$var[$i][1] = trim($var[$i][1]);
				$var[$i][1] = tag_img_extractor($var[$i][1]);
				
				if (in_array(substr($var[$i][1], -4, 4), $media_ext)) {	
					if (!in_array($var[$i][1], $img_added)) {
						array_push($media_output, $var[$i][1]);
						array_push($img_added, $var[$i][1]);
					}
					$img_num++;
				} 
				
				if (in_array(substr($var[$i][0], -4, 4), $media_ext)) {	
					if (!in_array($var[$i][0], $img_added)) {
						array_push($media_output, $var[$i][0]);
						array_push($img_added, $var[$i][0]);
					}
					$img_num++;
				} elseif (in_array(substr($var[$i][0], -4, 4), $docs_ext)) {
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
				if (!in_array($var[$i][0], $docs_added)) {
					array_push($docs_output, $var[$i][0]);
					array_push($docs_added, $var[$i][0]);
				}
				$docs_count++;
			} elseif (in_array(substr($var[$i][0], -4, 4), $media_ext)) {	
				if (!in_array($var[$i][0], $img_added)) {
					array_push($media_output, $var[$i][0]);
					array_push($img_added, $var[$i][0]);
				}
				$img_num++;
				}
			addResources($websites, $names, $keywords, $added, $img_added, $docs_added);
			$i++;
		}

		$i=0;
		while ($i < sizeof($keywords)) {
			if (strpos($output, $keywords[$i]) !== false) {
				$path_base = $path . "/" . $names[$q];
				if (!file_exists($path)) mkdir(utf8_decode($path), 0777, true);
				if (!file_exists($path_base)) mkdir(utf8_decode($path_base), 0777, true);
				$scrapped = fopen($path . '/' . $names[$q] . '/' . valid_chars($on_websites[$k]) . '.html', "w") or die("¡Error al abrir " . $path_base . "/" . basename($on_websites[$k]) . ".html!");
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
						$img_file = file_get_contents_curl($media_output[$z]);
						$media_path = $path_base . "/imgs";
						if (!file_exists($path)) mkdir(utf8_decode($path), 0777, true);
						if (!file_exists($path_base)) mkdir(utf8_decode($path_base), 0777, true);
						if (!file_exists($media_path)) mkdir(utf8_decode($media_path), 0777, true);
						$get_img = fopen($media_path . "/" . valid_chars(basename(urldecode($media_output[$z]))), "w") or die("¡Error al abrir " . $media_path . "/" . basename(urldecode($media_output[$z])));
						fwrite($get_img, $img_file);
						fclose($get_img);
						$save_img_test++;
						$z++;
					}
				}
				if (isset($_POST['save_docs']) && $_POST['save_docs'] == 'Yes') {
					$z=0;
					while ($z < sizeof($docs_output)) {
						$doc_file = file_get_contents_curl($docs_output[$z]);
						$docs_path = $path_base . "/docs";
						if (!file_exists($path)) mkdir(utf8_decode($path), 0777, true);
						if (!file_exists($path_base)) mkdir(utf8_decode($path_base), 0777, true);
						if (!file_exists($docs_path)) mkdir(utf8_decode($docs_path), 0777, true);
						$get_doc = fopen($docs_path . "/" . valid_chars(basename(urldecode($docs_output[$z]))), "w") or die("¡Error al abrir " . $docs_path . "/" . basename($docs_output[$z]));
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
		$ejecuciones++;
	}
	$k=0;
	$q++;
}
	if (isset($_POST['show_info']) && $_POST['show_info'] == 'Yes') {
		echo "<br><br><br>";
		echo '<table class="table-fill"><tr><th colspan=2>Informe de ejecución</th></tr>';
		echo "<tr><td>Urls analizadas</td><td>" . $url_count . "</td></tr>";
		echo "<tr><td>Nuevas urls analizadas</td><td>" . $new_url_count . "</td></tr>";
		echo "<tr><td>Coincidencias y urls scrapeadas</td><td>" . $key_matches . "</td></tr>";
		echo "<tr><td>Imágenes guardadas</td><td>" . $save_img_test . "</td></tr>";
		echo "<tr><td>Documentos guardados</td><td>" . $save_doc_test . "</td></tr>";
		echo "<tr><td>Urls añadidas al registro</td><td>" . $added_count . "</td></tr>";
		echo "<tr><td>Urls compuestas</td><td>" . $comp_count . "</td></tr>";
		echo "<tr><td>Ejecuciones del script realizadas</td><td>" . $ejecuciones . "</td></tr>";
		echo "</table>";
		echo "<br><br><br>";
	}
} else {
	echo '<script language="javascript">alert("No se detecta conexión a internet. Conéctese e inténtelo de nuevo.");</script>';
} 
}
?>
