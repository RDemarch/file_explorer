<?php
$relative_path = "C:\\wamp64\\www\\rdemarch\\";
$selected = array();
if (isset($_POST['choisis'])) {

  $selected = explode("\\", $_POST["choisis"]);
  $clear = array();
  foreach($selected as $i => $v)
  {
  if(empty($v)) $clear[] = $i;
  if($v === "..")
  {
    $clear[] = $i;
    $clear[] = $i - 1;
  }
}
  foreach ($clear as $i) unset($selected[$i]);
    unset($i);
    unset($clear);
    $selected = array_values($selected);
  }

$path = implode("\\", $selected);

if (isset($_POST['save'])) {
  file_put_contents($relative_path . $path, $_POST['content']);
}
if (isset($_POST['file'])  AND $_POST['file'] === "file_create" AND !empty($_POST['name']) AND !file_exists($_POST['name'])) {
  file_put_contents($relative_path . $path . "\\" . $_POST['name'], "");
}
if (isset($_POST['file']) AND $_POST['file'] === "file_delete") {
  unlink($relative_path . $path . "\\" . $_POST['name']);
}
if (isset($_POST['dir'])  AND $_POST['dir'] === "dir_create" AND !empty($_POST['name']) AND !file_exists($_POST['name'])) {
  mkdir($relative_path . $path . "\\" . $_POST['name']);
}
function delete_dir($p) {
  foreach (scandir($p) as $value ) {
    if ($value === "." or $value === "..") continue;
    $f = $p . "\\" . $value;
    if (is_dir($f)) {
      delete_dir($f);
    }
    else {
      unlink($f);
    }
  }
  rmdir($p);
}
if (isset($_POST['dir']) AND $_POST['dir'] === "dir_delete") {
  delete_dir($relative_path . $path . "\\" . $_POST['name']);
}


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?= $path ?></title>
      <style>
        textarea {
          width: 90%;
        }
        body, html {
          position: relative;
          height: 100%;
        }

        * {
          box-sizing: border-box;
        }
        .inline {
          display: inline;
        }
    </style>
  </head>
  <body>
    <h6><?= $path ?></h6>
    <form method="post" action="">
    <?php


  if (is_dir($relative_path . $path) or empty($path)){

    echo "<input type=\"hidden\" name=\"choisis\" value=\"$path\"/><br>";
    echo '<input type="text" name="name">';
    echo "<button type=\"submit\" name=\"file\" value=\"file_create\">Créer Fichier</button>";
    echo "<button type=\"submit\" name=\"file\" value=\"file_delete\">Supprimer Fichier</button>";


    echo "<button type=\"submit\" name=\"dir\" value=\"dir_create\">Créer Dossier</button>";
    echo "<button type=\"submit\" name=\"dir\" value=\"dir_delete\">Supprimer Dossier</button><br>";

?>
<table>
  <th>nom</th>
  <th>type</th>
  <th>dernière modification</th>
  <th>poids</th>
<?php

    $dir_content = scandir($relative_path . $path);
    foreach ($dir_content as $item) {
      if ($item === ".") {
        continue;
      }
      elseif ($item === ".." AND empty($path)) {
        continue;
      }
        setlocale(LC_TIME, "fr_FR", "French");
        $datean = date ("d F Y H:i:s", filemtime ($relative_path . $path . "\\" . $item));
        $datefr = strftime("%d %B %G %T", strtotime($datean));
        if (!is_dir($relative_path . $path . "\\" . $item)) {
          $weight = filesize($relative_path . $path . "\\" . $item) . " octets";
        }
        else {
          $weight = "";
        }
        $type = mime_content_type($relative_path . $path . "\\" . $item);


        echo "<tr><td><button type=\"submit\" name=\"choisis\" value=\"$path\\$item\">$item</button></td><td>$type</td><td>$datefr</td><td>$weight</td><tr>";

    }
  }
  else {
    echo "<input type=\"hidden\" name=\"choisis\" value=\"$path\"/><br>";
    echo "<button type=\"submit\" name=\"choisis\" value=\"$path\\..\" class=\"inline\">..</button>";
    echo "<button type=\"submit\" name=\"save\" value=\"save\" class=\"inline\">Enregistrer</button><br>";
    echo "<textarea rows=\"36\" name=\"content\">".htmlentities(file_get_contents($relative_path . $path))."</textarea>";

  }

     ?>
   </table>
</form>
  </body>
</html>
