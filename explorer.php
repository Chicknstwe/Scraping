<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Scraping Beta 0.4 - Explorador</title> <!-- Código hecho 100% por Demetrio Carmona Derqui. Imágenes de freepik.com libres de licencias. -->
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

.table-title h3 {
   color: #fafafa;
   font-size: 30px;
   font-weight: 400;
   font-style:normal;
   font-family: "Roboto", helvetica, arial, sans-serif;
   text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
   text-transform:uppercase;
}


/*** Table Styles **/

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
    width: 29em;  
	height: 5em;
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

th.text-left {
  text-align: left;
}

th.text-center {
  text-align: center;
}

th.text-right {
  text-align: right;
}

td.text-left {
  text-align: left;
}

td.text-center {
  text-align: center;
}

td.text-right {
  text-align: right;
}
</style>
	<br>
	<br>
	<br>
<?php
	include 'resources.php';
	include 'functions.php';
	
	$root="matches";
	$open_root=opendir($root);
	while ($categoria = readdir($open_root)) {
		if($categoria!="." AND $categoria!="..") {
			$path="matches/" . $categoria . "";
			$carpeta=opendir($path);
			echo '<table class="table-fill">';
			echo '<tr bgcolor="00a699" width=\"200\">';
			echo '<th colspan=3><font color="#FFFFFF"><strong>'.utf8_encode($categoria).'</strong></font></th>';
			echo '</tr>';
			echo '<tr><td>Archivo</td><td>Palabras clave</td><td>Enlace</td></tr>';
			
			while ($archivo = readdir($carpeta)) {
				if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
					$url = ucfirst(urldecode($archivo));
					echo "<tr><td class=\"left-col\"><font>$url</font></td>";
					echo "<td width=\"160\">" . keywords_matches("./matches/" . $categoria . "/" . $archivo . "") . "</td>";
					echo "<td width=\"100\"><a href=\"matches/" . $categoria . "/" . $archivo . "\"" . ">Enlace</a></td>";
					echo "</tr>";	
				}
			}
			$name=utf8_encode($categoria);
			$img_path="matches/" . $categoria . "/imgs";
			if (file_exists($img_path)) {
				echo "<th colspan=3 align=\"center\"><font><strong>" . $name . " - Imágenes</strong></font></th>";
				$carpeta=opendir($img_path);
				while ($archivo = readdir($carpeta)) {
					if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
						$url = ucfirst(urldecode($archivo));
						echo "<tr><td class=\"left-col\"><font>$url</font></td>";
						echo "<td width=\"260\" colspan=2 align=\"center\"><a href=\"matches/" . $categoria . "/imgs/" . $archivo . "\"" . ">Enlace</a></td>";
						echo "</tr>";	
					}
				}
			}
			$doc_path="matches/" . $categoria . "/docs";
			if (file_exists($doc_path)) {
				echo "<th colspan=3><font><strong>" . $name . " - Documentos</strong></font></th>";
				$carpeta=opendir($doc_path);
				while ($archivo = readdir($carpeta)) {
					if($archivo!="." AND $archivo!=".." AND $archivo!="imgs" AND $archivo!="docs") {
						$url = ucfirst(urldecode($archivo));
						echo "<tr class=\"left-col\"><td width=\"400\"><font>$url</font></td>";
						echo "<td width=\"260\" colspan=2 align=\"center\"><a href=\"matches/" . $categoria . "/docs/" . $archivo . "\"" . ">Enlace</a></td>";
						echo "</tr>";	
					}
				}				
			}
			echo "</table>";
			echo "<br>";
			echo "<br>";
			echo "<br>";
			closedir($carpeta);
		}
	}
	closedir($open_root);
?>