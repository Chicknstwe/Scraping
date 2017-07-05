<?php

include 'functions.php';
$output = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  extract($_POST);

  fixResources();
  $output = "Todos los registros se han reiniciado (incluidas palabras clave y webs).";
}
?>


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
    width: 22em;  
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

td.info {
	word-wrap: break-word;
	max-width: 300px;
	min-width: 300px;
}

td.subm {
	text-align: center;
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
<br><br><br><table class="table-fill"><tr><th colspan=4>Opciones de configuración</th></tr>
<tr><td><form action="config_web.php" method="post" enctype="multipart/form-data">
	Añadir nueva web</td>
	<td><input type="text" placeholder="Nombre" name="web_new_name" /><br>
	<input type="text" placeholder="Url" name="web_new_url" /></td>
	<td colspan=2>Asegúrese de introducir los datos sobre url y nombre en el campo correcto. El campo url debe tener http:// o https:// al principio del mismo.</td>
	</tr>
	
<?php
	echo "<tr><td>Borrar web</td>";
	echo "<td><select name=\"url_select\" size=\"1\">";
    echo "<option value=\"Ninguna\" selected>Ninguna</option>";
	
	include 'resources.php';
	$size=sizeof($websites);
	$i=0;
	while ($i < $size) {
		if (isset($websites[$i])) {
			echo "<option value=\"" . $i . "\">" . utf8_encode($names[$i]) . " (" . utf8_encode($websites[$i]) . ")</option>";
		}
		$i++;
	}
    echo "</select></td>";
	
?>
	<td colspan=2>La web seleccionada será borrada del registro.</td></tr>
    <tr><td></td><td class="subm" colspan=3><input class="amp" type="submit" name="boton_web" value="Modificar registro de webs" /></td></tr>
</form>
<tr><td colspan=4></td></tr>
<tr><td><form action="config_key.php" method="post" enctype="multipart/form-data">

	Añadir palabra clave</td><td colspan=3><input placeholder="Palabra clave" type="text" name="keyword_new" /></td>
	
<?php
	echo "<tr><td>Borrar palabra clave </td>";
	echo "<td><select name=\"keyword_select\" size=\"1\">";
    echo "<option value=\"Ninguna\" selected>Ninguna</option>";
	
	include 'resources.php';
	$size=sizeof($keywords);
	$i=0;
	while ($i < $size) {
		if (isset($keywords[$i])) {
			echo "<option value=\"" . $i . "\">" . urldecode($keywords[$i]) . "</option>";
		}
		$i++;
	}
    echo "</select></td>";
	
?>
	<td class="info" colspan=2>La palabra clave seleccionada será borrada del registro.</td></tr>
    <tr><td></td><td class="subm" colspan=3><input type="submit" class="amp" name="boton_key" value="Modificar registro de palabras clave" /></td></tr>
</form>
<tr><td colspan=4></td></tr>

<tr><td colspan=2><form action="config_reg_reset.php" method="post" enctype="multipart/form-data">
    <input type="submit" class="amp" name="reg_reset" value="Reiniciar registros" />
</form></td><td colspan=2>Esto borrará sólo los registros usados para saber qué webs, imágenes y documentos se han procesado ya por el script.</td></tr>

<tr><td colspan=2><form action="config_reg_fix.php" method="post" enctype="multipart/form-data">
    <input type="submit" class="amp" name="reg_fix" value="Reiniciar todos los registros" /><br><br>
</form></td><td colspan=2>Esto borrará todos los registros, también las webs y palabras clave seleccionadas. En caso de error con los registros (resources.php), que puede darse tras parar el script sin que haya terminado, esto puede arreglar el problema.</td></tr>
<?php

echo '<tr><td colspan=4>' . $output . '</td></tr>';

?>
</table>
<br><br><br><br>